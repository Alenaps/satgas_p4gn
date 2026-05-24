@extends('layouts.konsuli')

@section('title', 'Tentang Kami - SATGAS P4GN UNILA')

@section('content')
{{-- Background Deep Navy --}}
<div class="bg-[#0f172a] min-h-screen font-sans relative overflow-hidden pb-20">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px] -z-0 -ml-64 -mt-64"></div>
    <div class="absolute top-1/2 right-0 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[100px] -z-0 -mr-48"></div>

    {{-- Hero Section --}}
    <div class="relative w-full h-[550px] overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 hover:scale-105" 
             style="background-image: url('{{ asset('assets/struktur-organisasi.jpeg') }}');">
        </div>
        
        {{-- Overlay Gradasi --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-slate-900/80 to-[#0f172a]"></div>

        <div class="relative h-full flex flex-col items-center justify-center text-center px-6 pb-24">
            <span class="px-4 py-1.5 bg-emerald-500/20 border border-emerald-500/30 rounded-full text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] mb-4 animate-fade-in">
                Profil Organisasi
            </span>
            <h1 class="text-white text-5xl md:text-6xl font-black tracking-tighter mb-4">TENTANG <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">KAMI</span></h1>
            <div class="w-20 h-1.5 bg-emerald-500 rounded-full mb-6"></div>
            <p class="text-slate-300 text-sm md:text-base max-w-2xl font-medium leading-relaxed">
                Mengenal lebih dekat SATGAS P4GN UNILA dalam mewujudkan lingkungan pendidikan yang bersih, aman, dan sehat dari bahaya narkoba.
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 relative z-10 -mt-24">
        {{-- VISI & MISI Grid --}}
        <div class="grid md:grid-cols-2 gap-8 mb-20">
            {{-- VISI --}}
            <div class="group bg-white rounded-[3rem] shadow-2xl p-10 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden border border-white/10">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-50 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                
                <div class="relative">
                    <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-200">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">VISI</h2>
                    <p class="text-slate-600 leading-relaxed text-lg font-bold italic group-hover:text-emerald-700 transition-colors">
                        “Menjadi Kampus Bersinar <br class="hidden lg:block"> (Bersih dari Narkoba)”
                    </p>
                </div>
            </div>

            {{-- MISI --}}
            <div class="group bg-slate-900 rounded-[3rem] shadow-2xl p-10 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden border border-white/5">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>

                <div class="relative">
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 border border-white/10">
                        <i class="fas fa-bullseye text-emerald-400 text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-black text-white mb-4 tracking-tight">MISI</h2>
                    <p class="text-slate-300 leading-relaxed text-lg font-bold italic group-hover:text-white transition-colors">
                        “Melakukan screening, edukasi, dan pelayanan dalam upaya P4GN di lingkungan kampus.”
                    </p>
                </div>
            </div>
        </div>

        {{-- STRUKTUR ORGANISASI --}}
        <div class="pt-16"> 
            
            {{-- Header Title Section --}}
            <div class="flex flex-col items-center text-center mb-16">
                <h2 class="text-white text-3xl md:text-4xl font-black tracking-tight mb-4 uppercase">
                    Struktur Organisasi
                </h2>
                
                {{-- Garis aksen pemanis --}}
                <div class="w-16 h-1 bg-gradient-to-r from-emerald-500 to-teal-300 rounded-full mb-5"></div>
                
                <p class="text-slate-400 text-sm font-semibold tracking-[0.25em] uppercase">
                    Dedikasi Untuk Almamater
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @php
                    $anggota = [
                        ['Ketua SATGAS', 'profile.jpg', 'Dr. Nama Ketua, M.Si.'],
                        ['Sekretaris', 'profile.jpg', 'Nama Sekretaris, S.Kom.'],
                        ['Koord. Pencegahan', 'profile.jpg', 'Nama Koordinator, M.Pd.'],
                        ['Koord. Pelaporan', 'profile.jpg', 'Nama Koordinator, S.H.'],
                        ['Koord. Konseling', 'profile.jpg', 'Nama Koordinator, M.Psi.'],
                        ['Koord. Dokumentasi', 'profile.jpg', 'Nama Koordinator, S.I.Kom.'],
                    ];
                @endphp

                @foreach($anggota as $a)
                <div class="group relative bg-white/5 backdrop-blur-sm rounded-[2.5rem] p-8 border border-white/10 hover:bg-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    {{-- Avatar Container --}}
                    <div class="relative w-32 h-32 mx-auto mb-6">
                        <div class="absolute inset-0 bg-emerald-500 rounded-[2.5rem] rotate-6 group-hover:rotate-0 transition-transform duration-500"></div>
                        <div class="relative w-full h-full bg-slate-800 rounded-[2.5rem] overflow-hidden border-2 border-white/20">
                             <img src="{{ asset('assets/' . $a[1]) }}"
                                  class="w-full h-full object-cover"
                                  alt="{{ $a[0] }}">
                        </div>
                    </div>

                    <div class="text-center">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 group-hover:text-emerald-600 mb-2 block">
                            {{ $a[0] }}
                        </span>
                        <h3 class="font-black text-white group-hover:text-slate-900 text-lg leading-snug transition-colors">
                            {{ $a[2] }}
                        </h3>
                        <div class="mt-4 flex justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-emerald-500 hover:text-white transition-all">
                                <i class="fab fa-linkedin-in text-xs"></i>
                            </a>
                            <a href="#" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-emerald-500 hover:text-white transition-all">
                                <i class="fab fa-instagram text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
    }
</style>
@endsection