<x-layouts.app title="Pengembalian Barang">
    <div class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold">Pengembalian Barang</h1>
                    <p class="mt-1 text-sm text-zinc-600">Proses barang yang sudah selesai dipinjam dan lihat riwayat
                        pengembalian.</p>
                </div>
            </div>
        </section>
        <br>

        <section class="grid grid-cols-3 gap-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-zinc-500">Menunggu Pengembalian</div>
                <div class="mt-2 text-3xl font-semibold">{{ $belumKembali->total() }}</div>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-zinc-500">Sudah Dikembalikan</div>
                <div class="mt-2 text-3xl font-semibold">{{ $sudahKembali->total() }}</div>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-zinc-500">Total Transaksi</div>
                <div class="mt-2 text-3xl font-semibold">{{ $belumKembali->total() + $sudahKembali->total() }}</div>
            </div>
        </section>

        <section>
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">Perlu Diproses</h2>
                <span
                    class="rounded-md bg-zinc-200 px-3 py-1 text-xs font-medium text-zinc-700">{{ $belumKembali->total() }}
                    data</span>
            </div>

            <div class="w-full overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-[700px] w-full text-left text-sm">
                    <thead class="bg-zinc-100 text-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Peminjam</th>
                            <th class="px-4 py-3">Barang</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Tanggal Pinjam</th>
                            <th class="px-4 py-3">Denda</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @forelse ($belumKembali as $pinjam)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $pinjam->user?->nama }}</td>
                                <td class="px-4 py-3">{{ $pinjam->barang?->nama_barang }}</td>
                                <td class="px-4 py-3">{{ $pinjam->jumlah_pinjam }}</td>
                                <td class="px-4 py-3">{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ is_null($pinjam->denda) ? '-' : 'Rp ' . number_format($pinjam->denda, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" data-open-return
                                        data-id="{{ $pinjam->id_pinjam }}"
                                        data-user="{{ $pinjam->user?->nama }}"
                                        data-barang="{{ $pinjam->barang?->nama_barang }}"
                                        data-jumlah="{{ $pinjam->jumlah_pinjam }}"
                                        data-tanggal="{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}"
                                        class="rounded-md bg-zinc-900 px-3 py-2 text-xs font-medium text-white hover:bg-zinc-700">Proses Kembali</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-zinc-500" colspan="6">Tidak ada barang yang
                                    sedang dipinjam.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $belumKembali->links() }}
            </div>
        </section>

        <!-- Modal admin proses pengembalian -->
        <div id="adminPengembalianModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6" aria-hidden="true">
            <div class="w-full max-w-xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-4">
                    <h2 id="adminReturnTitle" class="text-lg font-semibold">Proses Pengembalian</h2>
                    <button class="rounded-md px-2 py-1 text-xl leading-none hover:bg-zinc-100" type="button" data-close-pengembalian>&times;</button>
                </div>

                <form id="pengembalianForm" method="post" class="space-y-4 px-5 py-5">
                    @csrf
                    <p id="pengembalianInfo" class="text-sm text-zinc-600"></p>

                    <label class="block">
                        <span class="text-sm font-medium">Kondisi Pengembalian</span>
                        <select name="kondisi_pengembalian" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900">
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium">Denda (Rp)</span>
                        <input name="denda" type="number" step="0.01" min="0" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" placeholder="Masukkan jumlah denda jika ada">
                    </label>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" data-close-pengembalian class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100">Batal</button>
                        <button type="submit" class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700">Simpan & Proses</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('adminPengembalianModal');
                const form = document.getElementById('pengembalianForm');
                const info = document.getElementById('pengembalianInfo');

                document.querySelectorAll('[data-open-return]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const user = btn.dataset.user;
                        const barang = btn.dataset.barang;
                        const jumlah = btn.dataset.jumlah;
                        const tanggal = btn.dataset.tanggal;

                        info.textContent = `${user} — ${barang} (x${jumlah}) — dipinjam sejak ${tanggal}`;

                        form.action = '/peminjaman/' + encodeURIComponent(id) + '/kembali';
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                document.querySelectorAll('[data-close-pengembalian]').forEach(b => b.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }));

                modal.addEventListener('click', (e) => { if (e.target === modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } });
            });
        </script>

        <section>
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">Riwayat Pengembalian</h2>
                <span
                    class="rounded-md bg-zinc-200 px-3 py-1 text-xs font-medium text-zinc-700">{{ $sudahKembali->total() }}
                    data</span>
            </div>

            <div class="w-full overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-[760px] w-full text-left text-sm">
                    <thead class="bg-zinc-100 text-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Peminjam</th>
                            <th class="px-4 py-3">Barang</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Pinjam</th>
                            <th class="px-4 py-3">Kembali</th>
                            <th class="px-4 py-3">Denda</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @forelse ($sudahKembali as $pinjam)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $pinjam->user?->nama }}</td>
                                <td class="px-4 py-3">{{ $pinjam->barang?->nama_barang }}</td>
                                <td class="px-4 py-3">{{ $pinjam->jumlah_pinjam }}</td>
                                <td class="px-4 py-3">{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $pinjam->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ is_null($pinjam->denda) ? '-' : 'Rp ' . number_format($pinjam->denda, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded-md bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-800">Dikembalikan</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-zinc-500" colspan="7">Belum ada riwayat
                                    pengembalian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sudahKembali->links() }}
            </div>
        </section>
    </div>
</x-layouts.app>
