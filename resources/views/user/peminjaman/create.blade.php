<x-layouts.app title="Pinjam Barang">
    <div class="mx-auto max-w-xl rounded-lg border border-zinc-200 bg-white p-6">
        <h1 class="text-2xl font-semibold">Pinjam Barang</h1>

        <div class="mt-4 rounded-md bg-zinc-100 p-4 text-sm">
            <div class="font-medium">{{ $barang->nama_barang }}</div>
            <div class="mt-1 text-zinc-600">Stok tersedia: {{ $barang->jumlah }} | Kondisi: {{ ucfirst($barang->kondisi_barang) }}</div>
        </div>

        <form class="mt-6 space-y-4" method="post" action="{{ route('peminjaman.store', $barang) }}">
            @csrf
            <label class="block">
                <span class="text-sm font-medium">Jumlah Pinjam</span>
                <input class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" min="1" max="{{ $barang->jumlah }}" name="jumlah_pinjam" value="{{ old('jumlah_pinjam', 1) }}" required>
            </label>

            <label class="block">
                <span class="text-sm font-medium">Lama Pinjam (hari)</span>
                <input class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" min="1" max="365" name="lama_hari" value="{{ old('lama_hari', 1) }}" required>
            </label>
            <div class="flex gap-3">
                <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" type="submit">Pinjam</button>
                <a class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100" href="{{ route('user.dashboard') }}">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>
