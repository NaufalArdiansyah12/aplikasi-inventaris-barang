@csrf
<label class="block">
    <span class="text-sm font-medium">Nama Barang</span>
    <input class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" required>
</label>

<label class="block">
    <span class="text-sm font-medium">Jumlah Stok</span>
    <input class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" min="0" name="jumlah" value="{{ old('jumlah', $barang->jumlah ?? 0) }}" required>
</label>

<label class="block">
    <span class="text-sm font-medium">Kondisi Barang</span>
    <select class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" name="kondisi_barang" required>
        @foreach (['baik' => 'Baik', 'rusak' => 'Rusak'] as $value => $label)
            <option value="{{ $value }}" @selected(old('kondisi_barang', $barang->kondisi_barang ?? 'baik') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</label>

<label class="block">
    <span class="text-sm font-medium">Harga per Hari (Rp)</span>
    <input class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" step="0.01" min="0" name="harga_per_hari" value="{{ old('harga_per_hari', $barang->harga_per_hari ?? 0) }}" required>
</label>

<label class="block">
    <span class="text-sm font-medium">Foto Barang</span>
    <div id="formFotoDrop" class="mt-1 flex items-center justify-center rounded-md border-2 border-dashed border-zinc-300 p-4 text-center cursor-pointer">
        <input id="formFotoInput" class="absolute h-0 w-0 opacity-0" type="file" name="foto" accept="image/*">
        <div id="formFotoContent" class="w-full">
            <div class="text-sm text-zinc-500">Seret foto ke sini atau klik untuk memilih (maks 2 MB)</div>
        </div>
    </div>
    <div id="formFotoMsg" class="mt-2 text-sm text-red-600 hidden">File terlalu besar (lebih dari 2 MB).</div>
    <div id="formFotoPreview" class="mt-2 hidden">
        <img id="formFotoImg" src="" alt="Foto barang" class="h-24 w-24 object-cover rounded">
    </div>
    @if(!empty($barang->foto))
        <div class="mt-2">
            <img src="{{ asset('storage/'.$barang->foto) }}" alt="Foto barang" class="h-24 w-24 object-cover rounded">
        </div>
    @endif
</label>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const drop = document.getElementById('formFotoDrop');
        const input = document.getElementById('formFotoInput');
        const preview = document.getElementById('formFotoPreview');
        const img = document.getElementById('formFotoImg');
        const msg = document.getElementById('formFotoMsg');
        if (!drop) return;

        const MAX_BYTES = 2 * 1024 * 1024; // 2 MB

        const showFile = (file) => {
            if (!file) return;
            if (file.size > MAX_BYTES) {
                msg.classList.remove('hidden');
                preview.classList.add('hidden');
                img.src = '';
                return;
            }
            msg.classList.add('hidden');
            const url = URL.createObjectURL(file);
            img.src = url;
            preview.classList.remove('hidden');
        };

        drop.addEventListener('click', () => input.click());

        drop.addEventListener('dragover', (e) => { e.preventDefault(); drop.classList.add('bg-zinc-50'); });
        drop.addEventListener('dragleave', () => { drop.classList.remove('bg-zinc-50'); });
        drop.addEventListener('drop', (e) => {
            e.preventDefault(); drop.classList.remove('bg-zinc-50');
            const file = e.dataTransfer.files?.[0];
            if (file) {
                input.files = e.dataTransfer.files;
                showFile(file);
            }
        });

        input.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            showFile(file);
        });
    });
</script>

<div class="flex gap-3">
    <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" type="submit">Simpan</button>
    <a class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100" href="{{ route('barang.index') }}">Batal</a>
</div>
