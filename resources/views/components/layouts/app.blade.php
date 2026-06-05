<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Inventaris Barang' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen {{ request()->routeIs('login', 'register') ? 'bg-zinc-200' : 'bg-zinc-50' }} text-zinc-900 antialiased">
    
    @auth
        @if (auth()->user()->role === 'admin')
            <div class="min-h-screen bg-zinc-100 flex">
                
                <aside class="border-r border-zinc-800 bg-zinc-950 text-white sticky top-0 h-screen w-72 flex-shrink-0 z-40">
                    <div class="flex h-full flex-col">
                        <div class="border-b border-zinc-800 px-5 py-6">
                            <a href="{{ route('admin.dashboard') }}" class="block text-xl font-semibold tracking-tight">Inventaris Barang</a>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-zinc-500">Admin Panel</p>
                        </div>

                        <nav class="flex flex-col gap-2 px-3 py-4 text-sm flex-1 overflow-visible">
                            <a class="rounded-md px-3 py-2.5 font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <a class="rounded-md px-3 py-2.5 font-medium {{ request()->routeIs('barang.*') ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}" href="{{ route('barang.index') }}">Data Barang</a>
                            <a class="rounded-md px-3 py-2.5 font-medium {{ request()->routeIs('peminjaman.index') ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}" href="{{ route('peminjaman.index') }}">Peminjaman</a>
                            <a class="rounded-md px-3 py-2.5 font-medium {{ request()->routeIs('pengembalian.index') ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-300 hover:bg-zinc-800 hover:text-white' }}" href="{{ route('pengembalian.index') }}">Pengembalian</a>
                        </nav>

                        <div class="border-t border-zinc-800 p-3">
                            <div class="mb-3 rounded-md bg-zinc-900 px-3 py-3">
                                <div class="text-sm font-medium">{{ auth()->user()->nama }}</div>
                                <div class="mt-0.5 text-xs text-zinc-500">Administrator</div>
                            </div>
                            <form method="post" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full rounded-md bg-white px-3 py-2 text-sm font-medium text-zinc-950 hover:bg-zinc-200" type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                </aside>

                <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                    <main class="w-full px-4 py-6 sm:px-6 lg:px-8 flex-1 overflow-y-auto">
                        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-zinc-200 bg-white px-5 py-4 shadow-sm">
                            <div>
                                <div class="text-sm font-medium text-zinc-500">Admin Panel</div>
                                <div class="text-lg font-semibold text-zinc-950">{{ $title ?? 'Inventaris Barang' }}</div>
                            </div>
                            <div class="rounded-md bg-zinc-100 px-3 py-2 text-sm font-medium text-zinc-700">
                                {{ now()->format('d M Y') }}
                            </div>
                        </div>

                        {{-- Alert Notification --}}
                        @if (session('success'))
                            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (isset($errors) && $errors->any())
                            <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <header class="border-b border-zinc-200 bg-white">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
                    <a href="{{ route('user.dashboard') }}" class="text-lg font-semibold">
                        Inventaris Barang
                    </a>
                    <nav class="flex items-center gap-3 text-sm">
                        <a class="rounded-md px-3 py-2 hover:bg-zinc-100" href="{{ route('user.dashboard') }}">Barang</a>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-md bg-zinc-900 px-3 py-2 text-white hover:bg-zinc-700" type="submit">Logout</button>
                        </form>
                    </nav>
                </div>
            </header>

            <main class="mx-auto max-w-6xl px-4 py-8">
                @if (session('success'))
                    <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        @endif
    @else
        <main class="min-h-screen flex items-center justify-center">
            {{ $slot }}
        </main>
    @endauth

</body>
</html>