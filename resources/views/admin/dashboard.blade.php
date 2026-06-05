<x-layouts.app title="Dashboard Admin">
    <div class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Dashboard Admin</h1>
                    <p class="mt-1 text-sm text-zinc-600">Ringkasan stok, peminjaman, dan pengembalian barang.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100"
                        href="{{ route('pengembalian.index') }}">Pengembalian</a>
                    <a class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700"
                        href="{{ route('barang.index') }}">Kelola Barang</a>
                </div>
            </div>
        </section>
        <br>

        <section class="flex gap-4 flex-nowrap overflow-x-auto items-stretch">
            <div class="flex-1 min-w-0 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm h-full">
                <div class="text-sm font-medium text-zinc-500">Jenis Barang</div>
                <div class="mt-3 text-3xl font-semibold">{{ $totalBarang }}</div>
            </div>
            <div class="flex-1 min-w-0 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm h-full">
                <div class="text-sm font-medium text-zinc-500">Total Stok</div>
                <div class="mt-3 text-3xl font-semibold">{{ $stokTersedia }}</div>
            </div>
            <div class="flex-1 min-w-0 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm h-full">
                <div class="text-sm font-medium text-zinc-500">Stok Habis</div>
                <div class="mt-3 text-3xl font-semibold">{{ $barangHabis }}</div>
            </div>
            <div class="flex-1 min-w-0 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm h-full">
                <div class="text-sm font-medium text-zinc-500">Sedang Dipinjam</div>
                <div class="mt-3 text-3xl font-semibold">{{ $peminjamanAktif }}</div>
            </div>
            <div class="flex-1 min-w-0 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm h-full">
                <div class="text-sm font-medium text-zinc-500">Dikembalikan</div>
                <div class="mt-3 text-3xl font-semibold">{{ $pengembalianSelesai }}</div>
            </div>
            
        </section>

        <section>
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">Peminjaman Terbaru</h2>
                <a class="text-sm font-medium text-zinc-700 hover:text-zinc-950"
                    href="{{ route('peminjaman.index') }}">Lihat semua</a>
            </div>
            @include('admin.peminjaman.table', ['peminjaman' => $peminjamanTerbaru, 'showActions' => true])
        </section>
    </div>
</x-layouts.app>
