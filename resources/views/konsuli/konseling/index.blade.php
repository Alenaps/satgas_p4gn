@extends('layouts.konsuli')

@section('title', 'Ruang Ceritamu')

@section('content')
{{-- Background menggunakan Deep Navy (#0f172a) agar seragam --}}
<div class="min-h-screen bg-[#0f172a] pb-32 font-sans overflow-x-hidden relative">
    {{-- Decorative Background Blobs --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px] -z-10 -mr-64 -mt-64"></div>
    <div class="absolute bottom-1/4 left-0 w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-[100px] -z-10 -ml-48"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">

        {{-- Alerts --}}
        @if(session('success'))
        <div class="max-w-4xl mx-auto bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-xl text-emerald-400 px-6 py-4 rounded-3xl mb-12 flex items-center shadow-2xl animate-fade-in-down">
            <div class="bg-emerald-500 text-white p-2 rounded-full mr-4 text-xs">
                <i class="fas fa-check"></i>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Header --}}
        <div class="mb-20 text-center max-w-3xl mx-auto animate-fade-in">
            <div class="inline-block px-5 py-2 mb-6 rounded-full bg-emerald-500/10 backdrop-blur-md border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                Hello, {{ auth()->user()->name ?? 'Sahabat' }}!
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-tight">
                Ruang <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Amanmu</span> untuk Bercerita
            </h1>
            <p class="text-lg text-slate-400 leading-relaxed font-medium">
                Setiap langkah kecilmu dihargai di sini. Mari lanjutkan perjalanan menuju dirimu yang lebih tenang.
            </p>
        </div>

        {{-- GRID CONTAINER: Pending & Active --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start mb-24">

            {{-- Section 1: PENDING --}}
            @if($sessions->where('status', 'pending')->count() > 0)
            <div class="w-full">
                <div class="flex flex-col items-center mb-10">
                    <div class="flex items-center gap-3 bg-white/5 px-6 py-3 rounded-2xl border border-white/10 backdrop-blur-sm">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <h2 class="text-xl font-black text-white tracking-tight">Lagi Ditunggu Nih..</h2>
                    </div>
                </div>
                
                <div class="flex flex-col gap-8 items-center">
                    @foreach($sessions->where('status', 'pending') as $session)
                    <div class="w-full max-w-[420px] bg-white rounded-[3rem] p-8 shadow-2xl shadow-black/20 border border-white/5 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                        <div class="relative">
                            <div class="flex items-start justify-between mb-8">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-tr from-amber-100 to-orange-50 p-1.5 shadow-lg rotate-3 group-hover:rotate-0 transition-all duration-500 overflow-hidden">
                                    @if($session->konselor->foto)
                                        <img src="{{ asset('storage/' . $session->konselor->foto) }}" class="w-full h-full object-cover rounded-[2.2rem]">
                                    @else
                                        <div class="w-full h-full bg-white rounded-[2.2rem] flex items-center justify-center text-amber-400">
                                            <i class="fas fa-user-astronaut text-4xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-4 py-1.5 rounded-full uppercase tracking-[0.1em] border border-amber-100">Pending</span>
                                </div>
                            </div>
                            <h3 class="font-black text-slate-900 text-2xl mb-1 truncate">{{ $session->konselor->nama }}</h3>
                            <p class="text-xs text-slate-400 mb-8 font-bold uppercase tracking-widest">Diajukan {{ $session->created_at->diffForHumans() }}</p>
                            <div class="bg-amber-50/80 rounded-[2rem] p-5 border border-dashed border-amber-200">
                                <p class="text-xs text-amber-700 font-bold leading-relaxed text-center italic">
                                    "Sedang menanti konfirmasi dari Konselor pilihanmu."
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Section 2: ACTIVE --}}
            @if($sessions->where('status', 'active')->count() > 0)
            <div class="w-full">
                <div class="flex flex-col items-center mb-10">
                    <div class="flex items-center gap-3 bg-emerald-500/10 px-6 py-3 rounded-2xl border border-emerald-500/20 backdrop-blur-sm">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <h2 class="text-xl font-black text-emerald-400 tracking-tight">Sedang Berlangsung</h2>
                    </div>
                </div>

                <div class="flex flex-col gap-8 items-center">
                    @foreach($sessions->where('status', 'active') as $session)
                    <div class="w-full max-w-[420px] bg-white rounded-[3rem] p-8 shadow-2xl shadow-emerald-500/10 border border-white/5 relative overflow-hidden group hover:-translate-y-2 transition-all duration-500">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-50/50 rounded-full -mr-20 -mt-20"></div>
                        <div class="relative">
                            <div class="flex items-center gap-6 mb-10">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-gradient-to-br from-emerald-400 to-teal-500 p-1.5 shadow-xl shadow-emerald-200/50 rotate-[-3deg] group-hover:rotate-0 transition-transform overflow-hidden">
                                    @if($session->konselor->foto)
                                        <img src="{{ asset('storage/' . $session->konselor->foto) }}" class="w-full h-full object-cover rounded-[2.2rem]">
                                    @else
                                        <div class="w-full h-full bg-emerald-600 rounded-[2.2rem] flex items-center justify-center text-white">
                                            <i class="fas fa-smile text-4xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-black text-slate-900 text-xl truncate leading-tight mb-2">{{ $session->konselor->nama }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Ruang Terbuka</p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3 mb-10">
                                <div class="flex items-center justify-between text-sm bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <span class="text-slate-400 font-black px-1 uppercase text-[10px] tracking-widest">Waktu Mulai</span>
                                    <span class="text-slate-900 font-black">{{ $session->started_at?->format('H:i WIB') ?? '--:--' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('konsuli.konseling.chat', $session->id) }}" 
                               class="flex items-center justify-center w-full bg-slate-900 hover:bg-emerald-600 text-white py-5 rounded-[2rem] transition-all duration-300 font-black text-xs uppercase tracking-widest group-hover:shadow-xl group-hover:shadow-emerald-500/20">
                                Lanjutkan Cerita <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Section 3: COMPLETED (Teks Di Tengah) --}}
        @if($sessions->where('status', 'completed')->count() > 0)
        <div class="mt-32 mb-16 max-w-5xl mx-auto">
            <div class="flex flex-col items-center mb-12">
                <div class="bg-emerald-500/10 p-4 rounded-[1.5rem] text-emerald-400 border border-emerald-500/20 shadow-sm mb-4 backdrop-blur-sm">
                    <i class="fas fa-book-open text-2xl"></i>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight text-center">Jejak Ceritamu</h2>
                <div class="w-20 h-1 bg-emerald-500/30 rounded-full mt-4"></div>
            </div>

            <div class="bg-white rounded-[3.5rem] shadow-2xl shadow-black/30 border border-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Konselor Sahabat</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kapan?</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Durasi Cerita</th>
                                <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($sessions->where('status', 'completed') as $session)
                            <tr class="hover:bg-emerald-50/50 transition-colors group">
                                <td class="px-8 py-8">
                                    <div class="flex items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 mr-5 overflow-hidden shadow-sm group-hover:scale-110 transition-transform border-2 border-white">
                                            @if($session->konselor->foto)
                                                <img src="{{ asset('storage/' . $session->konselor->foto) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-white">
                                                    <i class="fas fa-user text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-base font-black text-slate-900">{{ $session->konselor->nama }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">ID: {{ $session->konselor->npm_nip }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 whitespace-nowrap">
                                    <div class="text-sm font-black text-slate-700">{{ $session->started_at?->translatedFormat('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">Pukul {{ $session->started_at?->format('H:i') }}</div>
                                </td>
                                <td class="px-8 py-8 whitespace-nowrap">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-widest">
                                        <i class="far fa-clock mr-2 text-emerald-500"></i>
                                        {{ $session->started_at && $session->ended_at ? $session->started_at->diffForHumans($session->ended_at, true) : '-' }}
                                    </span>
                                </td>
                                <td class="px-8 py-8 whitespace-nowrap text-right">
                                    <a href="{{ route('konsuli.konseling.konsuli.riwayat.show', $session->id) }}" 
                                       class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-slate-100 text-slate-900 hover:border-emerald-500 hover:text-emerald-600 rounded-2xl transition-all text-[11px] font-black uppercase tracking-widest shadow-sm hover:shadow-md">
                                        Detail Sesi <i class="fas fa-chevron-right ml-2 text-[9px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Empty State --}}
        @if($sessions->count() == 0)
        <div class="bg-white rounded-[4rem] shadow-2xl shadow-black/20 border border-white/10 p-16 md:p-24 text-center max-w-4xl mx-auto mt-10 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-50 rounded-full -mr-40 -mt-40 transition-transform group-hover:scale-110 duration-700"></div>
            <div class="relative z-10">
                <div class="w-44 h-44 bg-gradient-to-br from-emerald-100 to-teal-50 rounded-[3rem] flex items-center justify-center mx-auto mb-12 rotate-6 group-hover:rotate-0 transition-transform duration-700 shadow-xl shadow-emerald-100/50">
                    <i class="fas fa-feather-alt text-emerald-500 text-7xl"></i>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-6 tracking-tight">Mulai Lembaran Baru</h3>
                <p class="text-slate-500 mb-12 text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                    Halaman ini masih kosong, tapi tenang saja. Ini adalah awal dari perjalananmu. Ceritakan bebanmu, kita cari solusinya bersama.
                </p>
                <a href="{{ route('konsuli.konselor.index') }}" 
                   class="inline-flex items-center justify-center bg-slate-900 hover:bg-emerald-600 text-white px-12 py-6 rounded-[2.5rem] transition-all duration-300 font-black text-sm uppercase tracking-[0.2em] shadow-2xl hover:-translate-y-2">
                    Cari Konselor <i class="fas fa-heart ml-3 text-red-400"></i>
                </a>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in { animation: fade-in 1.2s ease-out forwards; }
</style>
@endsection