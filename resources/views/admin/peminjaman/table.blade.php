<div class="w-full overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm">
    <table class="min-w-[760px] w-full text-left text-sm">
        <thead class="bg-zinc-100 text-zinc-700">
            <tr>
                <th class="px-4 py-3">Peminjam</th>
                <th class="px-4 py-3">Barang</th>
                <th class="px-4 py-3">Jumlah</th>
                <th class="px-4 py-3">Pinjam</th>
                <th class="px-4 py-3">Kembali</th>
                <th class="px-4 py-3">Status</th>
                @if ($showActions ?? false)
                    <th class="px-4 py-3"></th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200">
            @forelse ($peminjaman as $pinjam)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $pinjam->user?->nama }}</td>
                    <td class="px-4 py-3">{{ $pinjam->barang?->nama_barang }}</td>
                    <td class="px-4 py-3">{{ $pinjam->jumlah_pinjam }}</td>
                    <td class="px-4 py-3">{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $pinjam->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3 capitalize">{{ $pinjam->status_pinjam }}</td>
                    @if ($showActions ?? false)
                        <td class="px-4 py-3 text-right">
                            @if (in_array($pinjam->status_pinjam, ['dipinjam', 'menunggu_konfirmasi']))
                                <button type="button" data-open-return
                                    data-id="{{ $pinjam->id_pinjam }}"
                                    data-user="{{ $pinjam->user?->nama }}"
                                    data-barang="{{ $pinjam->barang?->nama_barang }}"
                                    data-jumlah="{{ $pinjam->jumlah_pinjam }}"
                                    data-tanggal="{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}"
                                    data-status="{{ $pinjam->status_pinjam }}"
                                    class="rounded-md border border-zinc-300 px-3 py-2 text-xs font-medium hover:bg-zinc-100">Proses Pengembalian</button>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td class="px-4 py-6 text-center text-zinc-500" colspan="{{ ($showActions ?? false) ? 7 : 6 }}">Belum ada data peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
