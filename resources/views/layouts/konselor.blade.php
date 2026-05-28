<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Konselor')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased">

<div class="flex min-h-screen">

    {{-- Overlay gelap (hanya muncul di mobile saat sidebar terbuka) --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"
         onclick="toggleSidebar()">
    </div>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 w-64
                  -translate-x-full
                  md:relative md:translate-x-0 md:z-auto
                  bg-gradient-to-b from-green-600 to-green-700
                  text-white flex flex-col shadow-xl
                  transition-transform duration-300 ease-in-out">

        {{-- Tombol tutup (X) — hanya tampil di mobile --}}
        <button onclick="toggleSidebar()"
                class="md:hidden absolute top-4 right-4 text-white hover:text-green-200 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
        </button>

        {{-- Logo --}}
        <div class="flex flex-col items-center px-4 py-6 border-b border-green-500">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg mb-3">
                <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo" class="w-12 h-12">
            </div>
            <div class="text-center">
                <h1 class="text-lg font-bold">SATGAS P4GN UNILA</h1>
                <p class="text-xs opacity-90 mt-1">SISTEM INFORMASI<br>KONSELING DAN PELAPORAN<br>UNIVERSITAS LAMPUNG</p>
            </div>
        </div>

        {{-- Profil Pengguna --}}
        <div class="px-4 py-4 border-b border-green-500">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 p-3 hover:bg-green-500 rounded-lg transition-colors group">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md overflow-hidden">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-green-600 text-xl"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate group-hover:text-green-50">{{ auth()->user()->nama }}</p>
                    <p class="text-xs opacity-75 truncate">{{ auth()->user()->npm_nip }}</p>
                    <p class="text-xs opacity-90 bg-green-800 px-2 py-0.5 rounded-full inline-block mt-1">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>
                </div>
            </a>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('konselor.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.dashboard') ? 'bg-green-500' : '' }}">
                <i class="fas fa-home w-5"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('konselor.publikasi.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.publikasi.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-newspaper w-5"></i>
                <span>Publikasi</span>
            </a>

            <a href="{{ route('konselor.laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.laporan.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span>Laporan</span>
            </a>

            {{-- Statistik Layanan --}}
            @php $isStatistikActive = request()->routeIs('konselor.statistik.*'); @endphp
            <div x-data="{ open: {{ $isStatistikActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ $isStatistikActive ? 'bg-green-500' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span>Statistik Layanan</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-4 space-y-1 border-l-2 border-green-400 pl-3">
                    <a href="{{ route('konselor.statistik.konseling') }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.statistik.konseling') ? 'bg-green-500' : '' }}">
                        <i class="fas fa-heart-pulse w-4 text-xs"></i>
                        <span>E-Konseling</span>
                    </a>
                    <a href="{{ route('konselor.statistik.laporan') }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.statistik.laporan') ? 'bg-green-500' : '' }}">
                        <i class="fas fa-file-chart-column w-4 text-xs"></i>
                        <span>Laporan</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('konselor.konseling.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.konseling.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-comments w-5"></i>
                <span>Konseling</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-4 border-t border-green-500">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <footer class="text-center text-xs px-4 py-3 border-t border-green-500 opacity-75">
            © {{ date('Y') }} SATGAS P4GN UNILA
        </footer>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Topbar Mobile --}}
        <header class="md:hidden bg-green-600 text-white px-4 py-3 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo" class="w-8 h-8 bg-white rounded-full p-0.5">
                <span class="font-bold text-sm">SATGAS P4GN</span>
            </div>
            <button onclick="toggleSidebar()" class="text-white hover:text-green-200 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </header>

        <main class="flex-1 p-6 md:p-10 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</div>

{{-- Scripts --}}
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')

</body>
</html>