<x-layouts.app title="Laporan Peminjaman">
    <div class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold">Laporan Peminjaman</h1>
                    <p class="mt-1 text-sm text-zinc-600">Daftar peminjaman dan pengembalian barang.</p>
                </div>
                <form class="flex flex-wrap gap-2" method="get">
                    <select class="rounded-md border border-zinc-300 px-3 py-2 text-sm" name="status">
                        <option value="">Semua status</option>
                        <option value="dipinjam" @selected(request('status') === 'dipinjam')>Dipinjam</option>
                        <option value="dikembalikan" @selected(request('status') === 'dikembalikan')>Dikembalikan</option>
                    </select>
                    <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section class="flex gap-4">
            <div class="flex-1 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-zinc-500">Total Denda Hari Ini</div>
                <div class="mt-2 text-2xl font-semibold">{{ is_null($totalDendaHariIni) ? 'Rp 0,00' : 'Rp ' . number_format($totalDendaHariIni, 2, ',', '.') }}</div>
            </div>
            <div class="flex-1 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-medium text-zinc-500">Penghasilan Sewa Hari Ini</div>
                <div class="mt-2 text-2xl font-semibold">{{ is_null($penghasilanSewaHariIni) ? 'Rp 0,00' : 'Rp ' . number_format($penghasilanSewaHariIni, 2, ',', '.') }}</div>
            </div>
        </section>

        <section>
            @include('admin.peminjaman.table')
            <div class="mt-4">
                {{ $peminjaman->links() }}
            </div>
        </section>
        
        <!-- Modal for admin to confirm return -->
        <div id="adminReturnModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6" aria-hidden="true">
            <div class="w-full max-w-md rounded-lg bg-white shadow-xl p-6">
                <h3 class="text-lg font-semibold">Konfirmasi Pengembalian</h3>
                <p id="modalInfo" class="mt-2 text-sm text-zinc-600"></p>

                <form id="adminReturnForm" method="post" class="mt-4">
                    @csrf
                    <div class="grid gap-3">
                        <label class="text-sm text-zinc-700">Kondisi Pengembalian</label>
                        <select name="kondisi_pengembalian" class="rounded-md border border-zinc-300 px-3 py-2 text-sm">
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>

                        <label class="text-sm text-zinc-700">Denda (Rp)</label>
                        <input name="denda" type="number" step="0.01" min="0" class="rounded-md border border-zinc-300 px-3 py-2 text-sm" placeholder="Masukkan jumlah denda jika ada">
                    </div>

                    <div class="mt-4 flex justify-end gap-3">
                        <button type="button" data-close-admin-return class="rounded-md border border-zinc-300 px-4 py-2 text-sm">Batal</button>
                        <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-sm text-white">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('adminReturnModal');
                const form = document.getElementById('adminReturnForm');
                const info = document.getElementById('modalInfo');

                document.querySelectorAll('[data-open-return]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const user = btn.dataset.user;
                        const barang = btn.dataset.barang;
                        const jumlah = btn.dataset.jumlah;
                        const tanggal = btn.dataset.tanggal;
                        const status = btn.dataset.status;

                        info.textContent = `${user} — ${barang} (x${jumlah}) — dipinjam sejak ${tanggal} — status: ${status}`;

                        form.action = '/peminjaman/' + encodeURIComponent(id) + '/kembali';
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                document.querySelectorAll('[data-close-admin-return]').forEach(b => b.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }));

                modal.addEventListener('click', (e) => { if (e.target === modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } });
            });
        </script>
    </div>
</x-layouts.app>
