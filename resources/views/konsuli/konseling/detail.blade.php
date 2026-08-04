@extends('layouts.konsuli')

@section('title', 'Detail Riwayat Konseling')

@section('content')
<style>
    .chat-container::-webkit-scrollbar { width: 5px; }
    .chat-container::-webkit-scrollbar-track { background: transparent; }
    .chat-container::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    .chat-bubble-shadow { box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.3); }
</style>

{{-- Background Deep Navy (#0f172a) --}}
<div class="w-full px-4 sm:px-6 lg:px-10 py-12 font-sans bg-[#0f172a] min-h-screen relative overflow-x-hidden">
    {{-- Decorative Background Blobs --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px] -z-0 -mr-64 -mt-64"></div>
    <div class="absolute bottom-1/4 left-0 w-[400px] h-[400px] bg-blue-500/5 rounded-full blur-[100px] -z-0 -ml-48"></div>

    <div class="max-w-6xl mx-auto relative z-10">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6 animate-fade-in">
            <div>
                <a href="{{ route('konsuli.konseling.index') }}" 
                   class="inline-flex items-center gap-2 text-slate-400 hover:text-emerald-400 mb-4 transition-all group text-xs font-black uppercase tracking-widest">
                    <i class="fas fa-arrow-left group-hover:-translate-x-2 transition-transform"></i>
                    <span>Kembali ke Riwayat</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">Detail Sesi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Konseling</span></h1>
                <p class="text-slate-400 text-sm mt-2 font-medium">Tinjau kembali percakapan berharga Anda di ruang aman ini.</p>
            </div>
            
            <div class="flex items-center gap-4 bg-white/5 backdrop-blur-xl p-3 px-5 rounded-[2rem] border border-white/10 shadow-2xl">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                    <i class="far fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase text-slate-400 font-bold tracking-widest leading-none mb-1.5">Sesi Selesai Pada</p>
                    <p class="text-sm font-bold text-slate-200">{{ $session->ended_at?->translatedFormat('d M Y') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-10 items-start">
            
            {{-- Sidebar Kiri: Info Konselor & Keamanan --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Card Konselor --}}
                <div class="bg-slate-800/20 backdrop-blur-md rounded-3xl shadow-2xl border border-white/5 p-8 text-center relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-700/20 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                    
                    <div class="relative">
                        <div class="w-24 h-24 mx-auto mb-5 p-1 rounded-full bg-gradient-to-tr from-emerald-500/20 to-teal-500/20 border border-emerald-500/20 rotate-3 group-hover:rotate-0 transition-all duration-500">
                            <div class="w-full h-full rounded-full bg-slate-900 p-0.5 overflow-hidden border border-slate-700/50">
                                @if($session->konselor->foto)
                                    <img src="{{ asset('storage/' . $session->konselor->foto) }}" class="w-full h-full object-cover rounded-full">
                                @else
                                    <div class="w-full h-full bg-slate-800/50 rounded-full flex items-center justify-center text-slate-500">
                                        <i class="fas fa-user-tie text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <h3 class="font-bold text-white text-xl mb-1">{{ $session->konselor->nama }}</h3>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase tracking-widest mb-6">Konselor Sahabat</p>
                        
                        <div class="grid grid-cols-2 gap-3 text-left border-t border-white/5 pt-6">
                            <div class="bg-slate-900/50 p-4 rounded-xl border border-white/5">
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider mb-1">Mulai</p>
                                <p class="text-sm font-bold text-slate-200">{{ $session->started_at?->format('H:i') ?? '--:--' }} <span class="text-[10px] text-slate-500">WIB</span></p>
                            </div>
                            <div class="bg-slate-900/50 p-4 rounded-xl border border-white/5">
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider mb-1">Selesai</p>
                                <p class="text-sm font-bold text-slate-200">{{ $session->ended_at?->format('H:i') ?? '--:--' }} <span class="text-[10px] text-slate-500">WIB</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Note Keamanan --}}
                <div class="bg-slate-800/20 backdrop-blur-md rounded-3xl p-8 shadow-2xl border border-white/5 relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03]">
                        <i class="fas fa-shield-alt text-8xl text-white"></i>
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center border border-emerald-500/20">
                            <i class="fas fa-fingerprint text-emerald-400"></i>
                        </div>
                        <h4 class="font-bold text-sm uppercase tracking-wider text-slate-200">End-to-End Encrypted</h4>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed font-medium">
                        Riwayat percakapan ini sepenuhnya dilindungi. Hanya Anda dan Konselor yang memiliki akses ke ruang aman ini demi menjaga privasi proses pemulihan Anda.
                    </p>
                </div>
            </div>

            {{-- Kolom Kanan: Transkrip Chat --}}
            <div class="lg:col-span-8 animate-fade-in-up">
                <div class="bg-slate-800/20 backdrop-blur-md rounded-3xl shadow-2xl border border-white/5 overflow-hidden flex flex-col h-[750px]">
                    
                    {{-- Chat Header --}}
                    <div class="px-8 py-5 border-b border-white/5 flex items-center justify-between bg-slate-900/50 sticky top-0 z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-2 h-2 rounded-full bg-slate-400 animate-pulse"></div>
                            <h2 class="font-bold text-slate-200 uppercase tracking-widest text-xs">Transkrip Obrolan</h2>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-800/50 px-3 py-1.5 rounded-md border border-white/5 tracking-wider uppercase">
                            Archived Session
                        </span>
                    </div>

                    {{-- Message Area --}}
                    <div class="flex-1 overflow-y-auto p-8 space-y-8 bg-transparent chat-container shadow-inner">
                        @forelse($session->messages as $msg)
                            @if($msg->sender_id == auth()->id())
                                {{-- Kanan (User/Konsuli) --}}
                                <div class="flex justify-end items-end gap-3">
                                    <div class="flex flex-col items-end gap-1.5 max-w-[80%]">
                                        <div class="bg-emerald-600 text-white px-5 py-3 rounded-2xl rounded-br-sm shadow-md border border-emerald-500/50">
                                            <p class="text-[13px] leading-relaxed break-all whitespace-pre-wrap">{{ $msg->message }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-500">{{ $msg->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                {{-- Kiri (Konselor) --}}
                                <div class="flex justify-start items-end gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex-shrink-0 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-user-tie text-xs text-emerald-400"></i>
                                    </div>
                                    <div class="flex flex-col gap-1.5 max-w-[80%]">
                                        <div class="bg-slate-800/80 text-slate-200 px-5 py-3 rounded-2xl rounded-bl-sm shadow-md border border-slate-700/50 backdrop-blur-sm">
                                            <p class="text-[13px] leading-relaxed break-all whitespace-pre-wrap">{{ $msg->message }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-500">{{ $msg->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-center opacity-40">
                                <div class="w-20 h-20 bg-slate-800/50 border border-slate-700/50 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                    <i class="far fa-comments text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Pesan tidak ditemukan</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Chat Footer --}}
                    <div class="p-6 bg-slate-900/50 border-t border-white/5">
                        <div class="bg-slate-800/50 rounded-xl py-3 px-5 flex items-center justify-center gap-3 border border-white/5">
                            <i class="fas fa-lock text-slate-500 text-xs"></i>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pesan terkunci secara otomatis setelah sesi berakhir</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fade-in-up { 
        from { opacity: 0; transform: translateY(20px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .animate-fade-in { animation: fade-in 1s ease-out forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection