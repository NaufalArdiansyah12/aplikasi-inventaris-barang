<x-layouts.app title="Pinjam Barang">
    <div class="mx-auto max-w-xl rounded-lg border border-zinc-200 bg-white p-6">
        <h1 class="text-2xl font-semibold">Pinjam Barang</h1>

        <div class="mt-4 rounded-md bg-zinc-100 p-4 text-sm">
            <div class="font-medium">{{ $barang->nama_barang }}</div>
            <div class="mt-1 text-zinc-600">Stok tersedia: {{ $barang->jumlah }} | Kondisi: {{ ucfirst($barang->kondisi_barang) }}</div>
        </div>

        <div class="mt-4 rounded-md border border-green-200 bg-green-50 p-4 text-sm">
            <div class="font-semibold text-green-900 mb-2">✅ Sewa Gratis</div>
            <div class="text-green-800">
                <p>Sewa barang ini <strong>gratis</strong>, hanya ada denda jika terlambat pengembalian.</p>
                <p class="mt-2">Denda keterlambatan: <strong>Rp {{ number_format($barang->denda_per_hari, 0, ',', '.') }}/hari</strong></p>
            </div>
        </div>

        <form class="mt-6 space-y-4" method="post" action="{{ route('peminjaman.store', $barang) }}">
            @csrf
            <label class="block">
                <span class="text-sm font-medium">Jumlah Pinjam</span>
                <input id="jumlahPinjam" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" min="1" max="{{ $barang->jumlah }}" name="jumlah_pinjam" value="{{ old('jumlah_pinjam', 1) }}" required>
            </label>

            <label class="block">
                <span class="text-sm font-medium">Lama Pinjam (hari)</span>
                <input id="lamaHari" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 outline-none focus:border-zinc-900" type="number" min="1" max="365" name="lama_hari" value="{{ old('lama_hari', 1) }}" required>
            </label>

            <div class="rounded-md bg-blue-50 border border-blue-200 p-4">
                <div class="text-sm">
                    <div class="text-zinc-600">Jadwal Pengembalian:</div>
                    <div class="text-lg font-semibold text-blue-900 mt-1">
                        <span id="tglKembaliEstimasi">{{ now()->addDays(1)->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-blue-200 text-sm">
                    <div class="text-zinc-600">Estimasi Denda Jika Terlambat:</div>
                    <div class="text-sm text-zinc-700 mt-1">
                        Rp <span id="dendaEstimasi">{{ number_format($barang->denda_per_hari, 0, ',', '.') }}</span>/hari × hari terlambat
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700" type="submit">Pinjam</button>
                <a class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100" href="{{ route('user.dashboard') }}">Batal</a>
            </div>
        </form>
    </div>

    <script>
        const dendaPerHari = {{ $barang->denda_per_hari }};
        const lamaHariInput = document.getElementById('lamaHari');
        const tglKembaliEstimasiSpan = document.getElementById('tglKembaliEstimasi');
        const dendaEstimasiSpan = document.getElementById('dendaEstimasi');

        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID').format(Math.round(value));
        }

        function updateEstimasi() {
            const lamaHari = parseInt(lamaHariInput.value) || 1;
            
            // Update tanggal estimasi pengembalian
            const today = new Date();
            const returnDate = new Date(today);
            returnDate.setDate(returnDate.getDate() + lamaHari);
            
            const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
            const formattedDate = returnDate.toLocaleDateString('id-ID', options);
            tglKembaliEstimasiSpan.textContent = formattedDate;

            // Update estimasi denda jika terlambat 1 hari
            const dendaEstimasi = dendaPerHari;
            dendaEstimasiSpan.textContent = formatCurrency(dendaEstimasi);
        }

        lamaHariInput.addEventListener('input', updateEstimasi);

        // Initialize on page load
        updateEstimasi();
    </script>
</x-layouts.app>
