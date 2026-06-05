<x-layouts.app title="Login">
    <div class="min-h-screen bg-zinc-200 px-4 py-8">
        <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-5xl items-center gap-8 md:grid-cols-[1fr_420px]">
            <section class="hidden md:block">
                <div class="inline-flex rounded-md bg-zinc-800 px-3 py-1 text-sm font-medium text-white">
                    Inventaris Barang
                </div>
                <h1 class="mt-5 text-4xl font-semibold leading-tight text-zinc-950">
                    Selamat datang di aplikasi inventaris.
                </h1>
                <p class="mt-4 max-w-xl text-base leading-7 text-zinc-700">
                    Masuk untuk mengelola data barang atau meminjam barang yang tersedia.
                </p>
                <div class="mt-8 grid max-w-lg grid-cols-3 gap-3">
                    <div class="rounded-lg border border-zinc-300 bg-zinc-100 p-4 shadow-sm">
                        <div class="text-2xl font-semibold">Barang</div>
                        <div class="mt-1 text-xs text-zinc-600">Data inventaris</div>
                    </div>
                    <div class="rounded-lg border border-zinc-300 bg-zinc-100 p-4 shadow-sm">
                        <div class="text-2xl font-semibold">Stok</div>
                        <div class="mt-1 text-xs text-zinc-600">Jumlah barang</div>
                    </div>
                    <div class="rounded-lg border border-zinc-300 bg-zinc-100 p-4 shadow-sm">
                        <div class="text-2xl font-semibold">Pinjam</div>
                        <div class="mt-1 text-xs text-zinc-600">Data peminjaman</div>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-zinc-300 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <p class="text-sm font-medium text-zinc-600">Login</p>
                    <h2 class="mt-1 text-2xl font-semibold">Masuk Akun</h2>
                    <p class="mt-1 text-sm text-zinc-600">Silakan isi username dan password.</p>
                </div>

                @if ($errors->has('username') || $errors->has('password'))
                    <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                        {{ $errors->first('username') ?: $errors->first('password') }}
                    </div>
                @endif

                <form class="space-y-4" method="post" action="{{ route('login.store') }}">
                    @csrf
                    <label class="block">
                        <span class="text-sm font-medium">Username</span>
                        <input class="mt-1 w-full rounded-md border border-zinc-300 bg-white px-3 py-2.5 outline-none transition focus:border-zinc-700 focus:ring-2 focus:ring-zinc-200" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium">Password</span>
                        <input class="mt-1 w-full rounded-md border border-zinc-300 bg-white px-3 py-2.5 outline-none transition focus:border-zinc-700 focus:ring-2 focus:ring-zinc-200" type="password" name="password" placeholder="Password" required>
                    </label>
                    <button class="w-full rounded-md bg-zinc-900 px-4 py-2.5 font-medium text-white transition hover:bg-zinc-700" type="submit">Masuk</button>
                </form>

                {{-- <div class="mt-5 rounded-md bg-zinc-100 p-3 text-sm text-zinc-700">
                    <div>Admin: <strong>admin</strong> / <strong>admin123</strong></div>
                    <div>User: <strong>user</strong> / <strong>user123</strong></div>
                </div> --}}

                <p class="mt-6 text-center text-sm text-zinc-600">
                    Belum punya akun?
                    <a class="font-semibold text-zinc-900 hover:text-zinc-700" href="{{ route('register') }}">Daftar</a>
                </p>
            </section>
        </div>
    </div>
</x-layouts.app>
