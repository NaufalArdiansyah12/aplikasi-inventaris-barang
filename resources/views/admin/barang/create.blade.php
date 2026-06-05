<x-layouts.app title="Tambah Barang">
    <div class="mx-auto max-w-xl rounded-lg border border-zinc-200 bg-white p-6">
        <h1 class="text-2xl font-semibold">Tambah Barang</h1>
        <form class="mt-6 space-y-4" method="post" action="{{ route('barang.store') }}" enctype="multipart/form-data">
            @include('admin.barang.form')
        </form>
    </div>
</x-layouts.app>
