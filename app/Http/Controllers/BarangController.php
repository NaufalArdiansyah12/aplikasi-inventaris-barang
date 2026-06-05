<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function index(): View
    {
        $this->ensureAdmin();

        return view('admin.barang.index', [
            'barang' => Barang::query()->orderBy('nama_barang')->get(),
        ]);
    }

    public function create(): View
    {
        $this->ensureAdmin();

        return view('admin.barang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $this->validated($request);
        if ($request->hasFile('foto')) {
            try {
                $data['foto'] = $request->file('foto')->store('barang', 'public');
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal mengunggah foto. Pastikan ukuran file tidak melebihi batas dan server mengizinkan upload.');
            }
        }

        Barang::query()->create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang): View
    {
        $this->ensureAdmin();

        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $this->validated($request);
        if ($request->hasFile('foto')) {
            try {
                // delete old photo
                if ($barang->foto) {
                    Storage::disk('public')->delete($barang->foto);
                }
                $data['foto'] = $request->file('foto')->store('barang', 'public');
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal mengunggah foto. Pastikan ukuran file tidak melebihi batas dan server mengizinkan upload.');
            }
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        $this->ensureAdmin();

        if ($barang->foto) {
            Storage::disk('public')->delete($barang->foto);
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_barang' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'kondisi_barang' => ['required', 'in:baik,rusak'],
            'harga_per_hari' => ['required', 'numeric', 'min:0'],
            // allow up to 5MB here; PHP ini limits must also permit this
            'foto' => ['nullable', 'image', 'max:5120'],
        ]);
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }
}
