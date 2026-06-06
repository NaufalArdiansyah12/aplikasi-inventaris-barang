<x-layouts.app title="Data Barang">
    <div class="space-y-6">
        <section class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold">Data Barang</h1>
                    <p class="mt-1 text-sm text-zinc-600">Kelola nama, stok, dan kondisi barang inventaris.</p>
                </div>
                <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" type="button" data-open-create>
                    Tambah Barang
                </button>
            </div>
        </section>
        <br>

        <section class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-200 px-5 py-4">
                <label class="block w-full md:max-w-sm">
                    <span class="sr-only">Cari barang</span>
                    <input id="barangSearch" class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-zinc-900" placeholder="Cari barang, kondisi, atau status...">
                </label>
                <div class="text-sm text-zinc-600">
                    <span id="barangCount">{{ $barang->count() }}</span> barang ditampilkan
                </div>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="min-w-[720px] w-full text-left text-sm">
                    <thead class="bg-zinc-100 text-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Kondisi</th>
                            <th class="px-4 py-3">Denda / Hari</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody id="barangTable" class="divide-y divide-zinc-200">
                        @forelse ($barang as $item)
                            <tr data-row data-search="{{ strtolower($item->nama_barang.' '.$item->jumlah.' '.$item->kondisi_barang.' '.$item->denda_per_hari.' '.$item->status) }}">
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
                                <td class="px-4 py-3">{{ number_format($item->denda_per_hari, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $item->status }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            class="rounded-md border border-zinc-300 px-3 py-2 text-xs font-medium hover:bg-zinc-100"
                                            type="button"
                                            data-open-view
                                            data-nama="{{ $item->nama_barang }}"
                                            data-jumlah="{{ $item->jumlah }}"
                                            data-kondisi="{{ ucfirst($item->kondisi_barang) }}"
                                            data-harga="{{ $item->denda_per_hari }}"
                                            data-foto="{{ $item->foto ? asset('storage/'.$item->foto) : '' }}"
                                            data-status="{{ $item->status }}"
                                        >
                                            Lihat
                                        </button>
                                        <button
                                            class="rounded-md border border-zinc-300 px-3 py-2 text-xs font-medium hover:bg-zinc-100"
                                            type="button"
                                            data-open-edit
                                            data-action="{{ route('barang.update', $item) }}"
                                            data-nama="{{ $item->nama_barang }}"
                                            data-jumlah="{{ $item->jumlah }}"
                                            data-kondisi="{{ $item->kondisi_barang }}"
                                            data-harga="{{ $item->denda_per_hari }}"
                                            data-foto="{{ $item->foto ? asset('storage/'.$item->foto) : '' }}"
                                        >
                                            Edit
                                        </button>
                                        <form method="post" action="{{ route('barang.destroy', $item) }}" onsubmit="return confirm('Hapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-md border border-red-300 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-50" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr data-empty-original><td class="px-4 py-6 text-center text-zinc-500" colspan="6">Belum ada barang.</td></tr>
                        @endforelse
                        <tr id="barangEmptySearch" class="hidden">
                            <td class="px-4 py-6 text-center text-zinc-500" colspan="6">Barang tidak ditemukan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div id="barangModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6" aria-hidden="true">
        <div class="w-full max-w-xl rounded-lg bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-4">
                <h2 id="barangModalTitle" class="text-lg font-semibold">Tambah Barang</h2>
                <button class="rounded-md px-2 py-1 text-xl leading-none hover:bg-zinc-100" type="button" data-close-modal>&times;</button>
            </div>

                <form id="barangForm" class="space-y-4 px-5 py-5" method="post" action="{{ route('barang.store') }}" enctype="multipart/form-data">
                @csrf
                <input id="barangFormMethod" type="hidden" name="_method" value="">

                <label class="block">
                    <span class="text-sm font-medium">Nama Barang</span>
                    <input id="modalNama" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" name="nama_barang" required>
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Jumlah Stok</span>
                    <input id="modalJumlah" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" min="0" name="jumlah" required>
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Kondisi Barang</span>
                    <select id="modalKondisi" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" name="kondisi_barang" required>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Denda per Hari (Rp)</span>
                    <input id="modalHarga" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" step="0.01" min="0" name="denda_per_hari" value="0" required>
                </label>

                <label class="block">
                    <span class="text-sm font-medium">Foto Barang</span>
                    <div id="modalFotoDrop" class="mt-1 flex items-center justify-center rounded-md border-2 border-dashed border-zinc-300 p-4 text-center cursor-pointer">
                        <input id="modalFoto" class="absolute h-0 w-0 opacity-0" type="file" name="foto" accept="image/*">
                        <div class="w-full">
                            <div class="text-sm text-zinc-500">Seret foto ke sini atau klik untuk memilih (maks 2 MB)</div>
                        </div>
                    </div>
                    <div id="modalFotoMsg" class="mt-2 text-sm text-red-600 hidden">File terlalu besar (lebih dari 2 MB).</div>
                    <div id="modalFotoPreview" class="mt-2 hidden">
                        <img id="modalFotoImg" src="" alt="Preview" class="h-24 w-24 object-cover rounded">
                    </div>
                </label>

                <div class="flex justify-end gap-3 pt-2">
                    <button class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100" type="button" data-close-modal>Batal</button>
                    <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" type="submit">Simpan</button>
                </div>
            </form>

            <div id="barangView" class="hidden px-5 py-5">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-md bg-zinc-100 p-4">
                        <dt class="text-xs font-medium uppercase text-zinc-500">Nama Barang</dt>
                        <dd id="viewNama" class="mt-1 text-base font-semibold"></dd>
                    </div>
                    <div class="rounded-md bg-zinc-100 p-4">
                        <dt class="text-xs font-medium uppercase text-zinc-500">Jumlah</dt>
                        <dd id="viewJumlah" class="mt-1 text-base font-semibold"></dd>
                    </div>
                    <div class="rounded-md bg-zinc-100 p-4">
                        <dt class="text-xs font-medium uppercase text-zinc-500">Kondisi</dt>
                        <dd id="viewKondisi" class="mt-1 text-base font-semibold"></dd>
                    </div>
                    <div class="rounded-md bg-zinc-100 p-4">
                        <dt class="text-xs font-medium uppercase text-zinc-500">Status</dt>
                        <dd id="viewStatus" class="mt-1 text-base font-semibold"></dd>
                    </div>
                    <div class="rounded-md bg-zinc-100 p-4">
                        <dt class="text-xs font-medium uppercase text-zinc-500">Denda / Hari (Rp)</dt>
                        <dd id="viewHarga" class="mt-1 text-base font-semibold"></dd>
                    </div>
                    <div class="rounded-md bg-zinc-100 p-4">
                        <dt class="text-xs font-medium uppercase text-zinc-500">Foto</dt>
                        <dd id="viewFoto" class="mt-1 text-base font-semibold"><img id="viewFotoImg" src="" alt="Foto" class="h-24 w-24 object-cover rounded hidden"></dd>
                    </div>
                </dl>
                <div class="mt-5 flex justify-end">
                    <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" type="button" data-close-modal>Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('barangModal');
            const title = document.getElementById('barangModalTitle');
            const form = document.getElementById('barangForm');
            const formMethod = document.getElementById('barangFormMethod');
            const view = document.getElementById('barangView');
            const nama = document.getElementById('modalNama');
            const jumlah = document.getElementById('modalJumlah');
            const kondisi = document.getElementById('modalKondisi');
            const harga = document.getElementById('modalHarga');
            const foto = document.getElementById('modalFoto');
            const fotoDrop = document.getElementById('modalFotoDrop');
            const fotoMsg = document.getElementById('modalFotoMsg');
            const fotoPreview = document.getElementById('modalFotoPreview');
            const fotoImg = document.getElementById('modalFotoImg');
            const search = document.getElementById('barangSearch');
            const rows = Array.from(document.querySelectorAll('[data-row]'));
            const count = document.getElementById('barangCount');
            const emptySearch = document.getElementById('barangEmptySearch');

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
            };

            document.querySelector('[data-open-create]')?.addEventListener('click', () => {
                title.textContent = 'Tambah Barang';
                form.action = @json(route('barang.store'));
                formMethod.value = '';
                form.reset();
                jumlah.value = 0;
                harga.value = 0;
                if (foto) { foto.value = ''; }
                if (fotoPreview) { fotoPreview.classList.add('hidden'); fotoImg.src = ''; }
                form.classList.remove('hidden');
                view.classList.add('hidden');
                openModal();
            });

            document.querySelectorAll('[data-open-edit]').forEach((button) => {
                button.addEventListener('click', () => {
                    title.textContent = 'Edit Barang';
                    form.action = button.dataset.action;
                    formMethod.value = 'PUT';
                    nama.value = button.dataset.nama;
                    jumlah.value = button.dataset.jumlah;
                    kondisi.value = button.dataset.kondisi;
                    harga.value = button.dataset.harga ?? 0;
                    if (foto) { foto.value = ''; }
                    if (fotoPreview) {
                        if (button.dataset.foto) {
                            fotoPreview.classList.remove('hidden');
                            fotoImg.src = button.dataset.foto;
                        } else {
                            fotoPreview.classList.add('hidden');
                            fotoImg.src = '';
                        }
                    }
                    form.classList.remove('hidden');
                    view.classList.add('hidden');
                    openModal();
                });
            });

            document.querySelectorAll('[data-open-view]').forEach((button) => {
                button.addEventListener('click', () => {
                    title.textContent = 'Detail Barang';
                    document.getElementById('viewNama').textContent = button.dataset.nama;
                    document.getElementById('viewJumlah').textContent = button.dataset.jumlah;
                    document.getElementById('viewKondisi').textContent = button.dataset.kondisi;
                    document.getElementById('viewStatus').textContent = button.dataset.status;
                    document.getElementById('viewHarga').textContent = Number(button.dataset.harga ?? 0).toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2});
                    const viewFotoImg = document.getElementById('viewFotoImg');
                    if (button.dataset.foto) {
                        viewFotoImg.src = button.dataset.foto;
                        viewFotoImg.classList.remove('hidden');
                    } else {
                        viewFotoImg.src = '';
                        viewFotoImg.classList.add('hidden');
                    }
                    form.classList.add('hidden');
                    view.classList.remove('hidden');
                    openModal();
                });
            });

            const MAX_BYTES = 2 * 1024 * 1024; // 2MB client warning

            const handleFile = (file) => {
                if (!file) { fotoPreview.classList.add('hidden'); fotoImg.src=''; fotoMsg.classList.add('hidden'); return; }
                if (file.size > MAX_BYTES) {
                    fotoMsg.classList.remove('hidden');
                    fotoPreview.classList.add('hidden');
                    fotoImg.src = '';
                    return;
                }
                fotoMsg.classList.add('hidden');
                const url = URL.createObjectURL(file);
                fotoImg.src = url;
                fotoPreview.classList.remove('hidden');
            };

            if (foto) {
                foto.addEventListener('change', (e) => {
                    const f = e.target.files[0];
                    handleFile(f);
                });
            }

            if (fotoDrop) {
                fotoDrop.addEventListener('click', () => foto.click());
                fotoDrop.addEventListener('dragover', (e) => { e.preventDefault(); fotoDrop.classList.add('bg-zinc-50'); });
                fotoDrop.addEventListener('dragleave', () => { fotoDrop.classList.remove('bg-zinc-50'); });
                fotoDrop.addEventListener('drop', (e) => {
                    e.preventDefault(); fotoDrop.classList.remove('bg-zinc-50');
                    const f = e.dataTransfer.files?.[0];
                    if (f) {
                        foto.files = e.dataTransfer.files;
                        handleFile(f);
                    }
                });
            }

            document.querySelectorAll('[data-close-modal]').forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            search?.addEventListener('input', () => {
                const keyword = search.value.trim().toLowerCase();
                let visible = 0;

                rows.forEach((row) => {
                    const match = row.dataset.search.includes(keyword);
                    row.classList.toggle('hidden', !match);
                    if (match) {
                        visible += 1;
                    }
                });

                count.textContent = visible;
                emptySearch.classList.toggle('hidden', visible !== 0 || rows.length === 0);
            });
        });
    </script>
</x-layouts.app>
