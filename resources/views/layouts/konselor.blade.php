<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Konselor')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased overflow-hidden">

<div class="flex h-screen w-full">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-green-600 to-green-700 text-white flex flex-col shadow-xl transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out">
        
        <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 text-white hover:text-green-200 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="flex flex-col items-center px-4 py-6 border-b border-green-500 mt-4 md:mt-0">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg mb-3">
                <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo" class="w-12 h-12">
            </div>
            <div class="text-center">
                <h1 class="text-lg font-bold">SATGAS P4GN UNILA</h1>
                <p class="text-xs opacity-90 mt-1">SISTEM INFORMASI<br>KONSELING DAN PELAPORAN<br>UNIVERSITAS LAMPUNG</p>
            </div>
        </div>

        <div class="px-4 py-4 border-b border-green-500">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 p-3 hover:bg-green-500 rounded-lg transition-colors group">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md overflow-hidden shrink-0">
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

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('konselor.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.dashboard') ? 'bg-green-500' : '' }}">
                <i class="fas fa-home w-5 text-center"></i>
                <span>Beranda</span>
            </a>
            
            <a href="{{ route('konselor.publikasi.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.publikasi.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-newspaper w-5 text-center"></i>
                <span>Publikasi</span>
            </a>
            
            <a href="{{ route('konselor.laporan.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.laporan.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-file-alt w-5 text-center"></i>
                <span>Laporan</span>
            </a>

            <a href="{{ route('konselor.konseling.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('konselor.konseling.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-comments w-5 text-center"></i>
                <span>Konseling</span>
            </a>
        </nav>

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

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="md:hidden bg-green-600 text-white p-4 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo" class="w-8 h-8 bg-white rounded-full p-0.5">
                <span class="font-bold text-sm">SATGAS P4GN</span>
            </div>
            <button onclick="toggleSidebar()" class="text-white focus:outline-none hover:text-green-200">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </header>

        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</div>

{{-- Scripts Section --}}
<script>
    // Fungsi untuk membuka/menutup sidebar di tampilan mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
@yield('scripts')

</body>
</html>