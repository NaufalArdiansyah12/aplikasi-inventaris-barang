<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class PeminjamanController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin();

        $query = Peminjaman::query()->with(['user', 'barang'])->latest('id_pinjam');

        if ($request->filled('status')) {
            $query->where('status_pinjam', $request->string('status'));
        }

        // Hitung total denda hari ini dan penghasilan sewa hari ini (untuk kartu di halaman laporan)
        $totalDendaHariIni = Peminjaman::query()
            ->where('status_pinjam', 'dikembalikan')
            ->whereNotNull('denda')
            ->whereDate('tanggal_kembali', now())
            ->sum('denda');

        $penghasilanSewaHariIni = 0; // Sewa gratis, jadi 0

        return view('admin.peminjaman.index', [
            'peminjaman' => $query->paginate(10)->withQueryString(),
            'totalDendaHariIni' => $totalDendaHariIni,
            'penghasilanSewaHariIni' => $penghasilanSewaHariIni,
        ]);
    }

    public function pengembalian(): View
    {
        $this->ensureAdmin();

        // Tampilkan peminjaman yang masih aktif atau yang telah diajukan pengembalian
        return view('admin.pengembalian.index', [
            'belumKembali' => Peminjaman::query()
                ->with(['user', 'barang'])
                ->whereIn('status_pinjam', ['dipinjam', 'menunggu_konfirmasi'])
                ->latest('id_pinjam')
                ->paginate(8, ['*'], 'dipinjam_page'),
            'sudahKembali' => Peminjaman::query()
                ->with(['user', 'barang'])
                ->where('status_pinjam', 'dikembalikan')
                ->latest('id_pinjam')
                ->paginate(8, ['*'], 'kembali_page'),
        ]);
    }

    public function create(Barang $barang): View
    {
        abort_if(Auth::user()?->role === 'admin', 403, 'Admin tidak boleh melakukan peminjaman.');

        return view('user.peminjaman.create', compact('barang'));
    }

    public function store(Request $request, Barang $barang): RedirectResponse
    {
        abort_if(Auth::user()?->role === 'admin', 403, 'Admin tidak boleh melakukan peminjaman.');

        $data = $request->validate([
            'jumlah_pinjam' => ['required', 'integer', 'min:1', 'max:' . $barang->jumlah],
            'lama_hari' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        if ($barang->kondisi_barang !== 'baik') {
            return back()->withErrors(['jumlah_pinjam' => 'Barang rusak tidak bisa dipinjam.']);
        }

        try {
            DB::transaction(function () use ($barang, $data): void {
                // Mengunci row barang untuk mencegah Race Condition / Double Booking
                $freshBarang = Barang::query()->lockForUpdate()->findOrFail($barang->id_barang);

                abort_if($freshBarang->jumlah < $data['jumlah_pinjam'], 422, 'Stok barang tidak cukup.');

                $procedureExecuted = false;

                // Coba jalankan via Stored Procedure jika tersedia
                if (DB::getDriverName() === 'mysql' && $this->routineAvailable('pinjam_barang')) {
                    try {
                        DB::statement('CALL pinjam_barang(?, ?, ?)', [
                            Auth::id(),
                            $freshBarang->id_barang,
                            $data['jumlah_pinjam'],
                        ]);
                        $procedureExecuted = true;
                    } catch (Throwable) {
                        // Jika routine bermasalah, biarkan fallback manual berjalan di bawah
                    }
                }

                // Fallback jika tidak menggunakan procedure atau procedure gagal tereksekusi
                if (!$procedureExecuted) {
                    $this->storeManualBorrow($freshBarang, $data['jumlah_pinjam'], $data['lama_hari']);
                }
            });
        } catch (Throwable $e) {
            return back()->withErrors(['jumlah_pinjam' => 'Gagal memproses peminjaman: ' . $e->getMessage()]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Peminjaman berhasil disimpan.');
    }

    public function return(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        // Pastikan hanya pemilik pinjaman atau admin yang bisa mengajukan/menangani pengembalian
        abort_unless($peminjaman->id_user === Auth::id() || Auth::user()?->role === 'admin', 403);

        if ($peminjaman->status_pinjam === 'dikembalikan') {
            return back()->with('info', 'Barang sudah dikembalikan sebelumnya.');
        }

        // Jika user biasa mengajukan pengembalian -> set status menunggu konfirmasi admin
        if (Auth::user()?->role !== 'admin') {
            if ($peminjaman->status_pinjam === 'menunggu_konfirmasi') {
                return back()->with('info', 'Pengembalian sudah diajukan, menunggu konfirmasi admin.');
            }

            $peminjaman->update([
                'status_pinjam' => 'menunggu_konfirmasi',
            ]);

            return back()->with('success', 'Permintaan pengembalian dikirim. Menunggu konfirmasi admin.');
        }

        // Jika admin yang memproses (konfirmasi), terima input kondisi
        $data = $request->validate([
            'kondisi_pengembalian' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($peminjaman, $data): void {
                $barang = Barang::query()->lockForUpdate()->findOrFail($peminjaman->id_barang);
                $barang->increment('jumlah', $peminjaman->jumlah_pinjam);

                // Hitung denda keterlambatan otomatis berdasarkan tanggal pengembalian aktual
                $tanggalKembaliAktual = now()->toDateString();
                $dendaKeterlambatan = $peminjaman->hitungDendaKeterlambatan($tanggalKembaliAktual);

                $peminjaman->update([
                    'tanggal_kembali' => $tanggalKembaliAktual,
                    'status_pinjam' => 'dikembalikan',
                    'kondisi_pengembalian' => $data['kondisi_pengembalian'] ?? null,
                    'denda' => $dendaKeterlambatan,
                ]);
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memproses pengembalian.');
        }

        return back()->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }

    private function routineAvailable(string $name): bool
    {
        try {
            return DB::table('information_schema.ROUTINES')
                ->where('ROUTINE_SCHEMA', DB::getDatabaseName())
                ->where('ROUTINE_NAME', $name)
                ->exists();
        } catch (Throwable) {
            return false;
        }
    }

    private function storeManualBorrow(Barang $barang, int $jumlahPinjam, int $lamaHari): void
    {
        Peminjaman::query()->create([
            'id_user' => Auth::id(),
            'id_barang' => $barang->id_barang,
            'jumlah_pinjam' => $jumlahPinjam,
            'lama_hari' => $lamaHari,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => now()->addDays($lamaHari)->toDateString(),
            'status_pinjam' => 'dipinjam', // Menambahkan default status agar eksplisit
        ]);

        $barang->decrement('jumlah', $jumlahPinjam);
    }
}