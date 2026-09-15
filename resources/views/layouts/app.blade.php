<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white font-sans text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <aside class="app-sidebar fixed inset-y-0 left-0 z-20 hidden w-56 flex-col bg-[#947dcb] text-white md:flex">
            <a class="border-b border-white/15 px-6 py-8 text-center text-xl font-bold text-white no-underline"
                href="{{ route('dashboard') }}">Gajiku</a>

            <nav class="flex-1 py-6" aria-label="Navigasi utama">
                <a class="flex items-center gap-3 px-6 py-4 text-base font-medium text-white no-underline"
                    href="{{ route('dashboard') }}">
                    <span class="text-2xl leading-none">▦</span>
                    Daftar Menu Gaji
                </a>

            </nav>

            <div class="border-t border-white/20 px-5 py-5">
                <a class="flex items-center justify-center gap-2 text-sm text-white no-underline"
                    href="{{ route('profile.edit') }}">
                    <span
                        class="grid h-8 w-8 place-items-center rounded-full bg-white/25 text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="mt-4 w-full border-0 bg-transparent text-sm text-white/90"
                        type="submit">Keluar</button>
                </form>
            </div>
        </aside>

        <div class="ml-0 min-w-0 flex-1 md:ml-56">
            <header class="flex h-16 items-center border-b border-slate-100 px-5 md:px-8">
                <button class="sidebar-toggle border-0 bg-transparent text-xl text-slate-600 md:hidden" type="button"
                    aria-label="Buka menu" aria-expanded="false">☰</button>
                <span class="ml-3 text-sm text-slate-500 md:hidden">{{ Auth::user()->name }}</span>
            </header>

            <main class="px-5 py-10 md:px-10 md:py-14">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
