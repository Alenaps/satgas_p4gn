<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') | SATGAS P4GN UNILA</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-slate-100 min-h-screen flex flex-col">

<!-- ================= NAVBAR ================= -->

<header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg sticky top-0 z-50" x-data="{ open: false }">

    <div class="max-w-7xl mx-auto">

        <div class="flex items-center justify-between h-20 px-4 lg:px-8">

            {{-- Logo --}}
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-md">
                    <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo" class="w-10 h-10">
                </div>
                <div class="text-white leading-tight">
                    <h1 class="text-lg font-bold">SATGAS P4GN UNILA</h1>
                    <p class="text-xs opacity-90">SISTEM INFORMASI KONSELING DAN PELAPORAN<br>UNIVERSITAS LAMPUNG</p>
                </div>
            </div>

            {{-- Menu (desktop) --}}
            <div class="hidden md:flex items-center gap-3">

                @auth

                    <a href="{{ url()->previous() }}"
                        class="px-5 py-2 rounded-lg bg-white text-gray-700 shadow hover:bg-gray-100 transition font-medium">
                        &larr; Kembali
                    </a>

                @else

                    <a href="{{ url('/') }}"
                        class="px-5 py-2 rounded-lg bg-white text-green-700 shadow hover:bg-green-50 transition font-medium mb-2">
                        Beranda
                    </a>

                    <a href="{{ route('login') }}"
                        class="px-5 py-2 rounded-lg bg-blue-600 text-white shadow hover:bg-blue-700 transition font-medium">
                        Login
                    </a>

                @endauth

            </div>

            {{-- Hamburger toggle (mobile) --}}
            <button type="button" @click="open = !open"
                class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-lg text-white hover:bg-white/10 transition"
                :aria-expanded="open" aria-label="Buka menu">
                <svg x-show="!open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>

        {{-- Menu (mobile dropdown) --}}
        <div
            x-show="open"
            x-cloak
            @click.outside="open = false"
            class="md:hidden border-t border-green-500 bg-green-700 px-4 py-4 space-y-3">

            @auth

                <a href="{{ url()->previous() }}"
                    class="block px-4 py-3 rounded-lg bg-white text-gray-700 shadow hover:bg-gray-100 transition text-center font-medium">
                    &larr; Kembali
                </a>

            @else

                <a href="{{ url('/') }}"
                    class="block px-4 py-3 rounded-lg bg-white text-green-700 shadow hover:bg-green-50 transition text-center font-medium mb-2">
                    Beranda
                </a>

                <a href="{{ route('login') }}"
                    class="block px-4 py-3 rounded-lg bg-blue-600 text-white shadow hover:bg-blue-700 transition text-center font-medium">
                    Login
                </a>

            @endauth

        </div>

    </div>

</header>

<!-- ================= CONTENT ================= -->

<main class="flex-1">

    @yield('content')

</main>

<!-- ================= FOOTER ================= -->

<footer class="bg-green-800 text-green-300 border-t border-green-700">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Logo & Identity -->
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ asset('assets/logo_unila.png') }}" class="w-12 h-12 bg-white rounded-full p-1 shadow" alt="Logo UNILA">
                    <div>
                        <h3 class="font-semibold text-white text-lg">SATGAS P4GN UNILA</h3>
                        <p class="text-sm opacity-80 leading-tight">Pencegahan, Pemberantasan, Penyalahgunaan & Peredaran Gelap Narkoba</p>
                    </div>
                </div>
                <p class="text-sm opacity-75">
                    Sistem Informasi Konseling dan Pelaporan
                    <br>Universitas Lampung.
                </p>
            </div>

            

            <!-- Contact -->
            <div class="md:col-span-2 flex flex-col ">
                <h3 class="text-white font-semibold mb-3">Kontak</h3>
                <p class="text-sm opacity-80">UPA Bimbingan Konseling Universitas Lampung</p>
                <p class="text-sm mt-2 opacity-70">Email: satgasp4gn@gmail.com</p>
                <p class="text-sm opacity-70">Alamat: Ruang UPA Bimbingan Konseling, Lantai 3 Gedung Rektorat Universitas Lampung</p>

                <div class="flex gap-4 mt-4">
                    <!-- Social Icons -->
                    <a href="https://www.instagram.com/satgasp4gnunila/" class="text-green-300 hover:text-white" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-green-400 text-xs py-4 border-t border-green-700">
            © {{ date('Y') }} SATGAS P4GN UNILA — Sistem Informasi Konseling & Pelaporan.
        </div>
    </footer>
@stack('scripts')
</body>
</html>