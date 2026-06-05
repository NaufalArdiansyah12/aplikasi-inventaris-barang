<x-layouts.app title="Dashboard User">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Daftar Barang</h1>
        <p class="text-sm text-zinc-600">Pilih barang yang kondisinya baik dan stoknya tersedia.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('itemDetailModal');
            const img = document.getElementById('detailFoto');
            const nama = document.getElementById('detailNama');
            const kondisi = document.getElementById('detailKondisi');
            const stok = document.getElementById('detailStok');
            const harga = document.getElementById('detailHarga');
            const statusEl = document.getElementById('detailStatus');
            const pinjamBtn = document.getElementById('detailPinjamBtn');

            document.querySelectorAll('[data-open-detail]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    nama.textContent = btn.dataset.nama;
                    kondisi.textContent = btn.dataset.kondisi || '-';
                    stok.textContent = btn.dataset.jumlah || '0';
                    harga.textContent = 'Rp ' + Number(btn.dataset.harga || 0).toLocaleString('id-ID', {minimumFractionDigits:2});
                    statusEl.textContent = btn.dataset.status || '-';
                    if (btn.dataset.foto) {
                        img.src = btn.dataset.foto;
                        img.classList.remove('hidden');
                    } else {
                        img.src = '';
                        img.classList.add('hidden');
                    }
                    const id = btn.dataset.id;
                    if (id) {
                        pinjamBtn.href = '/user/peminjaman/' + encodeURIComponent(id) + '/create';
                        pinjamBtn.classList.remove('hidden');
                    } else {
                        pinjamBtn.href = '#';
                    }
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            document.querySelectorAll('[data-close-detail]').forEach((b) => b.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }));

            modal.addEventListener('click', (e) => { if (e.target === modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } });
        });
    </script>

    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-100 text-zinc-700">
                <tr>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Stok</th>
                    <th class="px-4 py-3">Kondisi</th>
                    <th class="px-4 py-3">Harga / Hari</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($barang as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $item->nama_barang }}</td>
                        <td class="px-4 py-3">
                            @if($item->foto)
                                <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto" class="h-10 w-16 object-cover rounded">
                            @else
                                <div class="h-10 w-16 bg-zinc-100 rounded flex items-center justify-center text-xs text-zinc-400">No foto</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $item->jumlah }}</td>
                        <td class="px-4 py-3 capitalize">{{ $item->kondisi_barang }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($item->harga_per_hari ?? 0, 2, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $item->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="rounded-md border border-zinc-300 px-3 py-2 text-xs font-medium hover:bg-zinc-100" type="button" data-open-detail
                                    data-id="{{ $item->id_barang }}"
                                    data-nama="{{ $item->nama_barang }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    data-kondisi="{{ ucfirst($item->kondisi_barang) }}"
                                    data-status="{{ $item->status }}"
                                    data-harga="{{ $item->harga_per_hari ?? 0 }}"
                                    data-foto="{{ $item->foto ? asset('storage/'.$item->foto) : '' }}"
                                >Detail</button>
                                @if ($item->jumlah > 0 && $item->kondisi_barang === 'baik')
                                    <a class="rounded-md bg-zinc-900 px-3 py-2 text-xs font-medium text-white hover:bg-zinc-700" href="{{ route('peminjaman.create', $item) }}">Pinjam</a>
                                @else
                                    <span class="text-xs text-zinc-500">Tidak tersedia</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-center text-zinc-500" colspan="6">Belum ada barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <section class="mt-8">
        <h2 class="mb-3 text-lg font-semibold">Peminjaman Saya</h2>
        <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-100 text-zinc-700">
                    <tr>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Pinjam</th>
                        <th class="px-4 py-3">Kembali</th>
                        <th class="px-4 py-3">Total Bayar</th>
                        <th class="px-4 py-3">Kondisi Pengembalian</th>
                        <th class="px-4 py-3">Denda</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($peminjamanSaya as $pinjam)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $pinjam->barang?->nama_barang }}</td>
                            <td class="px-4 py-3">{{ $pinjam->jumlah_pinjam }}</td>
                            <td class="px-4 py-3">{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $pinjam->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                            @php
                                $hargaPerHari = $pinjam->barang?->harga_per_hari ?? 0;
                                $days = $pinjam->lama_hari ?? ($pinjam->tanggal_kembali ? $pinjam->tanggal_kembali->diffInDays($pinjam->tanggal_pinjam) : 1);
                                $totalBayar = $hargaPerHari * $days * ($pinjam->jumlah_pinjam ?? 1);
                            @endphp
                            <td class="px-4 py-3">Rp {{ number_format($totalBayar, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $pinjam->kondisi_pengembalian ?? '-' }}</td>
                            <td class="px-4 py-3">{{ is_null($pinjam->denda) ? '-' : 'Rp ' . number_format($pinjam->denda, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 capitalize">{{ $pinjam->status_pinjam }}</td>
                            <td class="px-4 py-3 text-right">
                                @if ($pinjam->status_pinjam === 'dipinjam')
                                    <form method="post" action="{{ route('peminjaman.return', $pinjam) }}">
                                        @csrf
                                        <button class="rounded-md border border-zinc-300 px-3 py-2 text-xs font-medium hover:bg-zinc-100" type="submit">Kembalikan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-6 text-center text-zinc-500" colspan="9">Belum ada peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    
    <!-- Detail modal for user items -->
    <div id="itemDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6" aria-hidden="true">
        <div class="w-full max-w-lg rounded-lg bg-white shadow-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-[140px_1fr] gap-4 items-start">
                <div class="flex items-center justify-center">
                    <img id="detailFoto" src="" alt="Foto" class="h-32 w-40 object-cover rounded hidden border border-zinc-200">
                </div>
                <div>
                    <h3 id="detailNama" class="text-xl font-semibold mb-2"></h3>
                    <dl class="grid grid-cols-1 gap-2 text-sm text-zinc-700">
                        <div class="flex justify-between">
                            <dt class="font-medium text-zinc-500">Kondisi</dt>
                            <dd id="detailKondisi" class="text-right"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium text-zinc-500">Stok</dt>
                            <dd id="detailStok" class="text-right"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium text-zinc-500">Harga / Hari</dt>
                            <dd id="detailHarga" class="text-right"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium text-zinc-500">Status</dt>
                            <dd id="detailStatus" class="text-right"></dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100" type="button" data-close-detail>Tutup</button>
                <a id="detailPinjamBtn" class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" href="#">Pinjam</a>
            </div>
        </div>
    </div>
</x-layouts.app>
