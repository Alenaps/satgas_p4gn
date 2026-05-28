@extends('layouts.konselor')

@section('title', 'Dashboard Konselor')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
    @keyframes fadeUp { from{opacity:0;transform:translateY(16px);} to{opacity:1;transform:translateY(0);} }
    .fade-up { animation: fadeUp .5s ease forwards; opacity: 0; }
    .fade-up:nth-child(1){animation-delay:.05s}
    .fade-up:nth-child(2){animation-delay:.10s}
    .fade-up:nth-child(3){animation-delay:.15s}
    .fade-up:nth-child(4){animation-delay:.20s}
    @keyframes ping { 75%,100%{transform:scale(2);opacity:0;} }
    .animate-ping { animation: ping 1.2s cubic-bezier(0,0,.2,1) infinite; }
</style>
@endpush

@section('content')
<div style="font-family:'Plus Jakarta Sans',sans-serif;">

{{-- ══ HEADER ══ --}}
<div class="bg-gradient-to-br from-slate-900 via-green-950 to-green-900 p-8 md:p-10 rounded-3xl mb-8 relative overflow-hidden">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
        <div>
            <h1 class="text-white text-2xl md:text-3xl font-extrabold flex items-center gap-3">
                <i class="fas fa-gauge-high opacity-80"></i> Dashboard Konselor
            </h1>
            <p class="text-white/60 mt-2 text-sm">
                Selamat datang, <span class="text-white/80 font-semibold">{{ auth()->user()->nama }}</span>
                — pantauan sesi hari ini
            </p>
        </div>
        <div class="text-white/40 text-xs text-right">
            <i class="fas fa-calendar mr-1"></i> {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>
</div>

{{-- ══ MINI SCORECARD ══ --}}
<p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">
    <i class="fas fa-bolt text-yellow-400 mr-1"></i> Ringkasan Saya
</p>
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-yellow-400 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Menunggu</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1" style="font-family:'JetBrains Mono',monospace;">{{ $totalPending }}</p>
            <p class="text-xs text-slate-400 mt-1">antrean pending</p>
        </div>
        @if($totalPending > 0)
        <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-yellow-400 rounded-full animate-ping"></span>
        @endif
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-blue-500 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-circle-dot"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Sesi Aktif</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1" style="font-family:'JetBrains Mono',monospace;">{{ $totalAktif }}</p>
            <p class="text-xs text-slate-400 mt-1">klien berjalan</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-red-500 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-envelope"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Pesan Baru</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1" style="font-family:'JetBrains Mono',monospace;">{{ $totalUnread }}</p>
            <p class="text-xs text-slate-400 mt-1">belum dibaca</p>
        </div>
        @if($totalUnread > 0)
        <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-red-400 rounded-full animate-ping"></span>
        @endif
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-emerald-500 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-circle-check"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Selesai</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1" style="font-family:'JetBrains Mono',monospace;">{{ $totalSelesai }}</p>
            <p class="text-xs text-slate-400 mt-1">sesi ditutup</p>
        </div>
    </div>

</div>

{{-- ══ ROW 2: ANTREAN PENDING + ACTIVE CHATS ══ --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-5">

    {{-- Antrean Pending --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 fade-up">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">
                <i class="fas fa-list-check text-yellow-400 mr-1"></i> Antrean Klien
                @if($totalPending > 0)
                <span class="ml-2 bg-yellow-100 text-yellow-700 text-[0.65rem] font-bold px-2 py-0.5 rounded-full">
                    {{ $totalPending }} baru
                </span>
                @endif
            </p>
            <a href="{{ route('konselor.konseling.index') }}"
               class="text-xs text-indigo-500 font-semibold hover:underline">
                Lihat semua <i class="fas fa-arrow-right ml-0.5"></i>
            </a>
        </div>

        @if($antreanPending->isEmpty())
        <div class="text-center py-10 text-slate-300">
            <i class="fas fa-check-circle text-3xl mb-2 block opacity-40"></i>
            <p class="text-sm">Tidak ada antrean pending</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($antreanPending as $sesi)
            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-yellow-200 hover:bg-yellow-50/40 transition-all">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 font-bold text-sm">
                    {{ strtoupper(substr($sesi->nama_konseli, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $sesi->nama_konseli }}</p>
                    <p class="text-xs text-slate-400 truncate">
                        <i class="fas fa-building mr-1"></i>{{ $sesi->nama_unit }}
                        <span class="mx-1">·</span>{{ $sesi->sivitas }}
                    </p>
                    <p class="text-[0.65rem] text-slate-300 mt-0.5" style="font-family:'JetBrains Mono',monospace;">
                        {{ \Carbon\Carbon::parse($sesi->created_at)->diffForHumans() }}
                    </p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <form method="POST" action="{{ route('konselor.konseling.approve', $sesi->id) }}">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600
                                   text-white text-xs font-bold transition-colors">
                            <i class="fas fa-check mr-1"></i> Terima
                        </button>
                    </form>
                    <form method="POST" action="{{ route('konselor.konseling.reject', $sesi->id) }}">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100
                                   text-red-600 text-xs font-bold border border-red-200 transition-colors">
                            <i class="fas fa-xmark mr-1"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Active Chats --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 fade-up">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">
                <i class="fas fa-comments text-blue-400 mr-1"></i> Chat Aktif
                @if($totalUnread > 0)
                <span class="ml-2 bg-red-100 text-red-600 text-[0.65rem] font-bold px-2 py-0.5 rounded-full">
                    {{ $totalUnread }} belum dibaca
                </span>
                @endif
            </p>
            <a href="{{ route('konselor.konseling.index') }}"
               class="text-xs text-indigo-500 font-semibold hover:underline">
                Lihat semua <i class="fas fa-arrow-right ml-0.5"></i>
            </a>
        </div>

        @if($activeChats->isEmpty())
        <div class="text-center py-10 text-slate-300">
            <i class="fas fa-comment-slash text-3xl mb-2 block opacity-40"></i>
            <p class="text-sm">Belum ada sesi aktif</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($activeChats as $chat)
            <div class="flex items-center gap-3 p-3 rounded-xl border
                        {{ $chat->unread_count > 0 ? 'border-red-200 bg-red-50/30' : 'border-slate-100' }}
                        hover:shadow-sm transition-all">
                <div class="relative shrink-0">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                        {{ strtoupper(substr($chat->nama_konseli, 0, 1)) }}
                    </div>
                    @if($chat->unread_count > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[0.55rem] font-bold rounded-full flex items-center justify-center">
                        {{ $chat->unread_count > 9 ? '9+' : $chat->unread_count }}
                    </span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $chat->nama_konseli }}</p>
                    <p class="text-xs text-slate-400 truncate">
                        <i class="fas fa-building mr-1"></i>{{ $chat->nama_unit }}
                    </p>
                    @if($chat->unread_count > 0)
                    <p class="text-[0.65rem] text-red-500 font-semibold mt-0.5">
                        <i class="fas fa-circle text-[0.4rem] mr-1"></i>
                        {{ $chat->unread_count }} pesan baru
                    </p>
                    @else
                    <p class="text-[0.65rem] text-slate-300 mt-0.5">
                        {{ \Carbon\Carbon::parse($chat->last_activity)->diffForHumans() }}
                    </p>
                    @endif
                </div>
                <a href="{{ route('konselor.konseling.chat', $chat->id) }}"
                   class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors
                          {{ $chat->unread_count > 0
                              ? 'bg-blue-500 hover:bg-blue-600 text-white'
                              : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- ══ ROW 3: KLIEN AKTIF + QUICK NOTES ══ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Klien Aktif Saat Ini --}}
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 fade-up">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-5 pb-3 border-b border-slate-100">
            <i class="fas fa-users text-emerald-400 mr-1"></i> Klien Aktif Saya
            <span class="ml-2 bg-emerald-100 text-emerald-700 text-[0.65rem] font-bold px-2 py-0.5 rounded-full">
                {{ $totalAktif }} klien
            </span>
        </p>

        @if($kliensAktif->isEmpty())
        <div class="text-center py-10 text-slate-300">
            <i class="fas fa-user-slash text-3xl mb-2 block opacity-40"></i>
            <p class="text-sm">Tidak ada klien aktif saat ini</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[0.68rem] font-bold uppercase tracking-widest">
                        <th class="px-3 py-3 rounded-l-lg">Klien</th>
                        <th class="px-3 py-3">Unit / Fakultas</th>
                        <th class="px-3 py-3">Sivitas</th>
                        <th class="px-3 py-3">Mulai</th>
                        <th class="px-3 py-3 rounded-r-lg text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($kliensAktif as $klien)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($klien->nama_konseli, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-700">{{ $klien->nama_konseli }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-3.5 text-slate-500 text-xs max-w-[140px] truncate">{{ $klien->nama_unit }}</td>
                        <td class="px-3 py-3.5">
                            <span class="bg-slate-100 text-slate-600 text-[0.65rem] font-bold px-2 py-1 rounded-lg">
                                {{ $klien->sivitas }}
                            </span>
                        </td>
                        <td class="px-3 py-3.5 text-slate-400 text-xs whitespace-nowrap" style="font-family:'JetBrains Mono',monospace;">
                            {{ \Carbon\Carbon::parse($klien->started_at)->format('d M') }}
                        </td>
                        <td class="px-3 py-3.5 text-center">
                            <a href="{{ route('konselor.konseling.chat', $klien->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                      bg-emerald-50 text-emerald-600 text-xs font-bold
                                      hover:bg-emerald-100 transition-colors">
                                <i class="fas fa-comment-dots"></i> Chat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Quick Notes --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 fade-up flex flex-col">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4 pb-3 border-b border-slate-100">
            <i class="fas fa-note-sticky text-amber-400 mr-1"></i> Catatan Cepat
        </p>
        <textarea id="quickNotes"
            class="flex-1 w-full resize-none text-sm text-slate-700 placeholder-slate-300
                   border border-slate-200 rounded-xl p-3 focus:outline-none focus:border-amber-300
                   focus:ring-2 focus:ring-amber-100 transition-all min-h-[180px]"
            placeholder="Tulis pengingat cepat di sini...&#10;Contoh: Cek perkembangan klien A besok pagi."></textarea>
        <div class="flex items-center justify-between mt-3">
            <p class="text-[0.65rem] text-slate-300" id="notesStatus">Tersimpan di perangkat ini</p>
            <button onclick="clearNotes()"
                class="text-[0.65rem] text-slate-400 hover:text-red-400 font-semibold transition-colors">
                <i class="fas fa-trash-can mr-1"></i> Hapus
            </button>
        </div>
    </div>

</div>

</div>
@endsection

@push('scripts')
<script>
const notesEl  = document.getElementById('quickNotes');
const statusEl = document.getElementById('notesStatus');
const KEY      = 'konselor_quick_notes_{{ auth()->id() }}';

notesEl.value = localStorage.getItem(KEY) ?? '';

let saveTimer;
notesEl.addEventListener('input', () => {
    clearTimeout(saveTimer);
    statusEl.textContent = 'Menyimpan...';
    statusEl.style.color = '#f59e0b';
    saveTimer = setTimeout(() => {
        localStorage.setItem(KEY, notesEl.value);
        statusEl.textContent = 'Tersimpan';
        statusEl.style.color = '#10b981';
        setTimeout(() => {
            statusEl.textContent = 'Tersimpan di perangkat ini';
            statusEl.style.color = '';
        }, 1500);
    }, 600);
});

function clearNotes() {
    if (!confirm('Hapus semua catatan?')) return;
    notesEl.value = '';
    localStorage.removeItem(KEY);
    statusEl.textContent = 'Catatan dihapus';
}
</script>
@endpush