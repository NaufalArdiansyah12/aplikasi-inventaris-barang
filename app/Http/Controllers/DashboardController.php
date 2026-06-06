<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        $this->ensureAdmin();

        return view('admin.dashboard', [
            'totalBarang' => Barang::query()->count(),
            'stokTersedia' => Barang::query()->sum('jumlah'),
            'barangHabis' => Barang::query()->where('jumlah', '<=', 0)->count(),
            'peminjamanAktif' => Peminjaman::query()->where('status_pinjam', 'dipinjam')->count(),
            'pengembalianSelesai' => Peminjaman::query()->where('status_pinjam', 'dikembalikan')->count(),
            // Total denda hari ini (dari kolom `denda`)
            'totalDendaHariIni' => Peminjaman::query()
                ->where('status_pinjam', 'dikembalikan')
                ->whereNotNull('denda')
                ->whereDate('tanggal_kembali', now())
                ->sum('denda'),
            'peminjamanTerbaru' => Peminjaman::query()
                ->with(['user', 'barang'])
                ->latest('id_pinjam')
                ->limit(5)
                ->get(),
        ]);
    }

    public function user(): View
    {
        return view('user.dashboard', [
            'barang' => Barang::query()->orderBy('nama_barang')->get(),
            'peminjamanSaya' => Peminjaman::query()
                ->with('barang')
                ->where('id_user', Auth::id())
                ->latest('id_pinjam')
                ->get(),
        ]);
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }
}
