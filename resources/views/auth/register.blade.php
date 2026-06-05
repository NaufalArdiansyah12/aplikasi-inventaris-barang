<x-layouts.app title="Register User">
    <div class="min-h-screen bg-zinc-200 px-4 py-8">
        <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-5xl items-center gap-8 md:grid-cols-[1fr_420px]">
            <section class="hidden md:block">
                <div class="inline-flex rounded-md bg-zinc-800 px-3 py-1 text-sm font-medium text-white">
                    Register User
                </div>
                <h1 class="mt-5 text-4xl font-semibold leading-tight text-zinc-950">
                    Buat akun baru.
                </h1>
                <p class="mt-4 max-w-xl text-base leading-7 text-zinc-700">
                    Setelah daftar, kamu bisa langsung masuk ke halaman user.
                </p>
            </section>

            <section class="rounded-lg border border-zinc-300 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <p class="text-sm font-medium text-zinc-600">Register</p>
                    <h2 class="mt-1 text-2xl font-semibold">Daftar Akun</h2>
                    <p class="mt-1 text-sm text-zinc-600">Isi data akun baru.</p>
                </div>

                <form class="space-y-4" method="post" action="{{ route('register.store') }}">
                    @csrf
                    <label class="block">
                        <span class="text-sm font-medium">Nama Lengkap</span>
                        <input class="mt-1 w-full rounded-md border border-zinc-300 bg-white px-3 py-2.5 outline-none transition focus:border-zinc-700 focus:ring-2 focus:ring-zinc-200" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" required autofocus>
                        @error('nama')
                            <span class="mt-1 block text-xs font-medium text-red-700">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium">Username</span>
                        <input class="mt-1 w-full rounded-md border border-zinc-300 bg-white px-3 py-2.5 outline-none transition focus:border-zinc-700 focus:ring-2 focus:ring-zinc-200" name="username" value="{{ old('username') }}" placeholder="Username" required>
                        @error('username')
                            <span class="mt-1 block text-xs font-medium text-red-700">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium">Password</span>
                        <input class="mt-1 w-full rounded-md border border-zinc-300 bg-white px-3 py-2.5 outline-none transition focus:border-zinc-700 focus:ring-2 focus:ring-zinc-200" type="password" name="password" placeholder="Minimal 6 karakter" required>
                        @error('password')
                            <span class="mt-1 block text-xs font-medium text-red-700">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium">Konfirmasi Password</span>
                        <input class="mt-1 w-full rounded-md border border-zinc-300 bg-white px-3 py-2.5 outline-none transition focus:border-zinc-700 focus:ring-2 focus:ring-zinc-200" type="password" name="password_confirmation" placeholder="Ulangi password" required>
                    </label>
                    <button class="w-full rounded-md bg-zinc-900 px-4 py-2.5 font-medium text-white transition hover:bg-zinc-700" type="submit">Daftar</button>
                </form>

                <p class="mt-6 text-center text-sm text-zinc-600">
                    Sudah punya akun?
                    <a class="font-semibold text-zinc-900 hover:text-zinc-700" href="{{ route('login') }}">Login</a>
                </p>
            </section>
        </div>
    </div>
</x-layouts.app>
