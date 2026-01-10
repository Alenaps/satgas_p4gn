@extends('layouts.konsuli')

@section('title', 'Dashboard Konsuli')

@section('content')

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }
    .animate-fadeInUp-delay {
        animation: fadeInUp 0.8s ease-out 0.2s backwards;
    }
</style>

<!-- HERO SECTION FULL BACKGROUND -->
<section class="relative min-h-screen flex items-center justify-center bg-gray-900">

    <!-- FULL BACKGROUND -->
    <div class="absolute inset-0">
        <img src="{{ asset('assets/bg_team.jpeg') }}" 
             alt="background" 
             class="w-full h-full object-cover object-center opacity-50">
    </div>

    <!-- CONTENT -->
    <div class="relative z-10 text-center px-4 max-w-3xl">
        <h2 class="text-4xl md:text-6xl font-bold text-white mb-8 drop-shadow-2xl animate-fadeInUp">
            Selamat Datang, Konsuli!
        </h2>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fadeInUp-delay">
            <a href="{{ route('konsuli.konseling.index') }}" 
               class="bg-blue-500 text-white px-8 py-3 rounded-full font-semibold shadow-xl hover:shadow-blue-500/50 hover:-translate-y-1 transition-all duration-300">
                Mulai Konseling
            </a>

            <a href="{{ route('konsuli.laporan.create') }}" 
               class="bg-green-600 text-white px-8 py-3 rounded-full font-semibold shadow-xl hover:shadow-green-500/50 hover:-translate-y-1 transition-all duration-300">
                Lapor Sekarang
            </a>
        </div>
    </div>

</section>


<!-- LAYANAN UTAMA -->
<section class="py-16 bg-gradient-to-b from-gray-900 to-gray-800">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="text-3xl md:text-5xl font-bold text-center text-white mb-10 animate-fadeInUp">
            Layanan Konsuli
        </h2>

        <div class="w-20 h-1 bg-blue-500 mx-auto mb-16 rounded-full animate-fadeInUp"></div>

        <div class="grid md:grid-cols-3 gap-10">

            <!-- === MULAI KONSELING === -->
            <div class="p-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl border border-white/10 
                        hover:shadow-blue-500/20 hover:-translate-y-2 transition duration-300 animate-fadeInUp">

                <div class="flex justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400" 
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path d="M12 6v6l4 2" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-white text-center mb-4">Mulai Konseling</h3>

                <p class="text-gray-300 text-sm text-center">
                    Dapatkan layanan konseling online bersama konselor profesional.
                </p>

                <div class="text-center mt-6">
                    <a href="{{ route('konsuli.konseling.index') }}"
                       class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold">
                        Buka Layanan
                    </a>
                </div>
            </div>

            <!-- === LAPOR SEKARANG === -->
            <div class="p-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl border border-white/10 
                        hover:shadow-blue-500/20 hover:-translate-y-2 transition duration-300 animate-fadeInUp">

                <div class="flex justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-white text-center mb-4">Lapor Sekarang</h3>

                <p class="text-gray-300 text-sm text-center">
                    Laporkan hal penting atau kasus secara aman dan rahasia.
                </p>

                <div class="text-center mt-6">
                    <a href="{{ route('konsuli.laporan.create') }}"
                       class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold">
                        Ajukan Laporan
                    </a>
                </div>
            </div>

            <!-- === INFORMASI & PUBLIKASI === -->
            <div class="p-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl border border-white/10 
                        hover:shadow-blue-500/20 hover:-translate-y-2 transition duration-300 animate-fadeInUp">

                <div class="flex justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path d="M4 5h16" />
                        <path d="M4 12h16" />
                        <path d="M4 19h16" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-white text-center mb-4">Informasi & publikasi</h3>

                <p class="text-gray-300 text-sm text-center">
                    Baca publikasi edukasi dan bahasan penting seputar kesehatan mental.
                </p>

                <div class="text-center mt-6">
                    <a href="{{ route('konsuli.publikasi.index') }}"
                       class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold">
                        Baca publikasi
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection