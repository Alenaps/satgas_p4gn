<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SATGAS P4GN UNILA')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body class="bg-white">

    <!-- NAVBAR -->
    <nav class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg" x-data="{ open: false }">

        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- LOGO -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-md">
                   <img src="{{ asset('assets/logo_unila.png') }}" class="w-10 h-10">
                </div>
                <div class="text-white leading-tight">
                    <h1 class="text-lg font-bold">SATGAS P4GN UNILA</h1>
                    <p class="text-xs opacity-90">
                        SISTEM INFORMASI KONSELING DAN PELAPORAN<br>
                        UNIVERSITAS LAMPUNG
                    </p>
                </div>
            </div>

            <!-- TOMBOL MOBILE -->
            <button @click="open = !open" class="lg:hidden text-white focus:outline-none">
                <svg x-show="!open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- MENU DESKTOP -->
            <ul class="hidden lg:flex items-center gap-1 px-4 text-sm font-medium text-white">

    {{-- BERANDA --}}
    <li>
        <a href="{{ route('konsuli.dashboard') }}"
           class="flex items-center h-10 px-3 rounded-lg transition duration-200
           hover:bg-white/10
           {{ Request::routeIs('konsuli.dashboard') ? 'bg-white/20 font-semibold' : '' }}">
            BERANDA
        </a>
    </li>

    {{-- TENTANG --}}
    <li>
        <a href="{{ route('konsuli.tentang') }}"
           class="flex items-center h-10 px-3 rounded-lg transition duration-200
           hover:bg-white/10
           {{ Request::routeIs('tentang') ? 'bg-white/20 font-semibold' : '' }}">
            TENTANG
        </a>
    </li>

    {{-- PUBLIKASI --}}
    <li>
        <a href="{{ route('konsuli.publikasi.index') }}"
           class="flex items-center h-10 px-3 rounded-lg transition duration-200
           hover:bg-white/10
           {{ Request::routeIs('konsuli.publikasi.index') ? 'bg-white/20 font-semibold' : '' }}">
            PUBLIKASI
        </a>
    </li>

    {{-- LAPORAN --}}
    <li>
        <a href="{{ route('konsuli.laporan.saya') }}"
           class="flex items-center h-10 px-3 rounded-lg transition duration-200
           hover:bg-white/10
           {{ Request::routeIs('konsuli.laporan.saya') ? 'bg-white/20 font-semibold' : '' }}">
            LAPORAN SAYA
        </a>
    </li>

    {{-- KONSELOR --}}
    <li>
        <a href="{{ route('konsuli.konselor.index') }}"
           class="flex items-center h-10 px-3 rounded-lg transition duration-200
           hover:bg-white/10
           {{ Request::routeIs('konsuli.konselor.index') ? 'bg-white/20 font-semibold' : '' }}">
            KONSELOR
        </a>
    </li>

    {{-- KONSELING --}}
    <li>
        <a href="{{ route('konsuli.konseling.index') }}"
           class="flex items-center h-10 px-3 rounded-lg transition duration-200
           hover:bg-white/10
           {{ Request::routeIs('konsuli.konseling.index') ? 'bg-white/20 font-semibold' : '' }}">
            KONSELING
        </a>
    </li>

    {{-- PROFILE --}}
    <li>
        <a href="{{ route('profile.index') }}"
           class="flex items-center h-10 px-3 rounded-lg transition duration-200
           hover:bg-white/10
           {{ Request::routeIs('profile.index') ? 'bg-white/20 font-semibold' : '' }}">
            PROFILE
        </a>
    </li>

    {{-- LOGOUT --}}
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="flex items-center h-10 px-3 rounded-lg bg-white/10 hover:bg-red-500 transition duration-200">
                LOGOUT
            </button>
        </form>
    </li>

</ul>
        </div>

        <!-- MOBILE MENU -->
        <div x-show="open" x-transition class="lg:hidden bg-green-600/90 backdrop-blur-md">
            <ul class="flex flex-col items-center py-4 space-y-2 text-white">

                <li><a href="{{ route('konsuli.dashboard') }}" class="px-4 py-2 hover:bg-white/15 rounded block">BERANDA</a></li>
                <li><a href="{{ route('tentang') }}" class="px-4 py-2 hover:bg-white/15 rounded block">TENTANG</a></li>
                <li><a href="{{ route('konsuli.publikasi.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">PUBLIKASI</a></li>
                <li><a href="{{ route('konsuli.laporan.saya') }}" class="px-4 py-2 hover:bg-white/15 rounded block">LAPORAN SAYA</a></li>
                <li><a href="{{ route('konsuli.konselor.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">KONSELOR</a></li>
                <li><a href="{{ route('konsuli.konseling.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">KONSELING</a></li>
                <li><a href="{{ route('profile.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">PROFILE</a></li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 hover:bg-white/15 rounded block">LOGOUT</button>
                    </form>
                </li>

            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- =========================== -->
    <!--           FOOTER            -->
    <!-- =========================== -->

    <footer class="bg-green-800 text-green-300 border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Logo -->
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ asset('assets/logo_unila.png') }}" class="w-12 h-12 bg-white rounded-full p-1 shadow">
                    <div>
                        <h3 class="font-semibold text-white text-lg">SATGAS P4GN UNILA</h3>
                        <p class="text-sm opacity-80 leading-tight">
                            Pencegahan & Pemberantasan Penyalahgunaan Narkoba
                        </p>
                    </div>
                </div>
                <p class="text-sm opacity-75">
                    Sistem Informasi Konseling dan Pelaporan  
                    <br>Universitas Lampung.
                </p>
            </div>

            <!-- Navigasi -->
            <div>
                <h3 class="text-white font-semibold mb-3">Navigasi</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('konsuli.dashboard') }}" class="hover:text-white">Beranda</a></li>
                    <li><a href="{{ route('tentang') }}" class="hover:text-white">Tentang</a></li>
                    <li><a href="{{ route('konsuli.publikasi.index') }}" class="hover:text-white">Publikasi</a></li>
                    <li><a href="{{ route('konsuli.konselor.index') }}" class="hover:text-white">Konselor</a></li>
                    <li><a href="{{ route('konsuli.konseling.index') }}" class="hover:text-white">Konseling</a></li>
                    <li><a href="{{ route('konsuli.laporan.create') }}" class="hover:text-white">Lapor</a></li>
                    <li><a href="{{ route('konsuli.laporan.saya') }}" class="hover:text-white">Laporan Saya</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h3 class="text-white font-semibold mb-3">Kontak</h3>
                <p class="text-sm opacity-80">UPA Bimbingan Konseling Universitas Lampung</p>
                <p class="text-sm mt-2 opacity-70">Email: satgasp4gn@gmail.com</p>
                <p class="text-sm opacity-70">Alamat: Gedung Rektorat Lt. 2, Universitas Lampung</p>

                <div class="flex gap-4 mt-4">
                    <!-- Social Icons -->
                    <a href="#" class="text-gray-300 hover:text-white" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.522-4.477-10-10-10S2 6.478 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898v-2.89h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33V21.88C18.343 21.128 22 16.99 22 12z"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/satgasp4gnunila/" class="text-gray-300 hover:text-white" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        <div class="text-center text-green-400 text-xs py-4 border-t border-gray-700">
            © {{ date('Y') }} SATGAS P4GN UNILA — Sistem Informasi Konseling & Pelaporan.
        </div>
    </footer>

    @stack('scripts')

</body>
</html>