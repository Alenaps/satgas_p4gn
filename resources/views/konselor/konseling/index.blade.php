@extends('layouts.konselor')

@section('title', 'Ruang Praktik')

@section('content')

    {{-- Decorative Background Blobs --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-300/20 rounded-full blur-[120px] -z-10 -mr-64 -mt-32"></div>
    <div class="absolute bottom-1/4 left-0 w-[400px] h-[400px] bg-teal-300/10 rounded-full blur-[100px] -z-10 -ml-48"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">

        {{-- Header --}}
        <div class="mb-12 animate-fade-in-down">
            <h1 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight mb-2">
                Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-700 to-teal-600">{{ auth()->user()->name ?? 'Konselor' }}</span>
            </h1>
            <p class="text-gray-600 font-medium text-lg">Selamat datang kembali. Mari bantu mereka menemukan ketenangan hari ini.</p>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="bg-[#f4f7f6] border-l-4 border-emerald-500 text-gray-800 px-6 py-4 rounded-2xl mb-8 flex items-center shadow-lg shadow-emerald-900/5 animate-fade-in-down">
            <div class="bg-emerald-200/50 p-2 rounded-full mr-4 text-emerald-700 text-sm">
                <i class="fas fa-check"></i>
            </div>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Grid: Pending & Active --}}
        <div class="bg-[#dce8e4]/60 p-6 md:p-8 rounded-[2.5rem] mb-12 shadow-sm border border-[#cddad5]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

                {{-- KOLOM 1: Permintaan Masuk --}}
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-amber-200/60 p-2.5 rounded-xl text-amber-700 shadow-sm">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h2 class="text-xl font-black text-gray-800 tracking-tight">Permintaan Masuk</h2>
                        </div>
                    </div>

                    {{-- Container selalu dirender agar JS bisa menargetkannya --}}
                    <div id="pending-list" class="flex flex-col gap-5 flex-1">
                        @forelse($pendingSessions as $session)
                        <div id="session-{{ $session->id }}"
                             class="bg-[#f4f7f6] rounded-[2rem] p-6 shadow-lg shadow-gray-200/50 border border-gray-100 relative overflow-hidden group transition-all hover:shadow-xl flex flex-col">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-100/50 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>

                            <div class="relative flex-1 flex flex-col">
                                <div class="flex items-center gap-4 mb-5">
                                    <div class="w-14 h-14 bg-amber-200/50 rounded-2xl p-0.5 flex-shrink-0">
                                        <div class="w-full h-full bg-[#f4f7f6] rounded-[0.9rem] overflow-hidden">
                                            @if($session->konseli->foto)
                                                <img src="{{ asset('storage/' . $session->konseli->foto) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-amber-500 bg-gray-50/50">
                                                    <i class="fas fa-user text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-black text-gray-800 text-lg truncate">{{ $session->konseli->nama }}</h3>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $session->konseli->npm_nip }}</p>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                <div class="flex gap-3 mt-auto pt-2">
                                    <form action="{{ route('konselor.konseling.approve', $session->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl transition-all font-black text-sm shadow-md">
                                            Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('konselor.konseling.reject', $session->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-transparent border-2 border-rose-200 text-rose-500 hover:bg-rose-100 hover:border-rose-300 py-3 rounded-xl transition-all font-black text-sm"
                                                onclick="return confirm('Tolak permintaan ini?')">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div id="empty-pending"
                             class="bg-[#f4f7f6]/60 border-2 border-dashed border-gray-300 rounded-[2.5rem] flex flex-col items-center justify-center p-10 text-center flex-1 min-h-[200px]">
                            <i class="fas fa-mug-hot text-3xl text-gray-400 mb-4"></i>
                            <h3 class="font-black text-gray-700">Kosong & Tenang</h3>
                            <p class="text-sm text-gray-500 font-medium">Belum ada permintaan masuk.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- KOLOM 2: Sesi Aktif --}}
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-emerald-200/60 p-2.5 rounded-xl text-emerald-700 shadow-sm">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h2 class="text-xl font-black text-gray-800 tracking-tight">Sesi Aktif</h2>
                        </div>
                    </div>

                    {{-- Container selalu dirender agar JS bisa menargetkannya --}}
                    <div id="active-list" class="flex flex-col gap-5 flex-1">
                        @forelse($activeSessions as $session)
                        <div id="session-{{ $session->id }}"
                             class="bg-[#f4f7f6] rounded-[2rem] p-6 shadow-lg shadow-gray-200/50 border border-gray-100 relative overflow-hidden group transition-all hover:shadow-xl flex flex-col">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-200/20 rounded-full -mr-16 -mt-16"></div>

                            <div class="relative flex-1 flex flex-col">
                                <div class="flex items-center gap-4 mb-5">
                                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-0.5 shadow-md">
                                        <div class="w-full h-full bg-[#f4f7f6] rounded-[0.9rem] overflow-hidden">
                                            @if($session->konseli->foto)
                                                <img src="{{ asset('storage/' . $session->konseli->foto) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-teal-600">
                                                    <i class="fas fa-smile text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-black text-gray-800 text-lg truncate">{{ $session->konseli->nama }}</h3>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest">Sedang Berlangsung</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-200/50 rounded-xl p-3 mb-5 flex justify-between items-center text-sm font-bold text-gray-700 border border-gray-200/50">
                                    <span class="text-gray-500 uppercase text-[10px] tracking-wider">Mulai:</span>
                                    <span>{{ $session->started_at->format('H:i') }} WIB</span>
                                </div>

                                <div class="flex-1"></div>

                                <div class="flex gap-3 mt-auto pt-2">
                                    <a href="{{ route('konselor.konseling.chat', $session->id) }}"
                                       class="flex-[2] bg-gray-800 hover:bg-emerald-700 text-white py-3 rounded-xl transition-all font-black text-sm flex items-center justify-center shadow-md">
                                        <i class="fas fa-paper-plane mr-2"></i> Buka Obrolan
                                    </a>
                                    <a href="{{ route('konselor.konseling.end-form', $session->id) }}"
                                       class="flex-1 bg-transparent border-2 border-gray-300 hover:border-gray-400 text-gray-600 hover:bg-gray-100 py-3 rounded-xl transition-all font-black text-sm flex items-center justify-center">
                                        <i class="fas fa-flag-checkered mr-1"></i> Akhiri
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div id="empty-active"
                             class="bg-[#f4f7f6]/60 border-2 border-dashed border-gray-300 rounded-[2.5rem] flex flex-col items-center justify-center p-10 text-center flex-1 min-h-[200px]">
                            <i class="fas fa-wind text-3xl text-gray-400 mb-4"></i>
                            <h3 class="font-black text-gray-700">Hening...</h3>
                            <p class="text-sm text-gray-500 font-medium">Tidak ada sesi aktif saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        {{-- Tabel Riwayat --}}
        @if($completedSessions->total() > 0 || request('search') || request('tanggal'))
        <div class="mt-16">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-300/70 p-2.5 rounded-xl text-gray-700 shadow-sm">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Jejak Pelayananmu</h2>
                </div>

                <form method="GET" action="{{ request()->url() }}" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2.5 bg-[#f4f7f6] border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-600 focus:border-transparent outline-none transition-all text-gray-700 placeholder-gray-400"
                               placeholder="Cari nama atau NPM/NIP...">
                    </div>

                    <div class="relative w-full sm:w-48">
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                               class="w-full px-4 py-2.5 bg-[#f4f7f6] border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-600 focus:border-transparent outline-none transition-all text-gray-600">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center gap-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        @if(request('search') || request('tanggal'))
                        <a href="{{ request()->url() }}" class="bg-rose-100 hover:bg-rose-200 text-rose-600 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center justify-center" title="Reset Filter">
                            <i class="fas fa-undo"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-[#f4f7f6] rounded-[2.5rem] shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                @if($completedSessions->total() > 0)
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full divide-y divide-gray-200/60">
                        <thead>
                            <tr>
                                <th class="px-6 py-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Nama Konseli</th>
                                <th class="px-6 py-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Waktu Sesi</th>
                                <th class="px-6 py-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Durasi</th>
                                <th class="px-6 py-5 text-center text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Status</th>
                                <th class="px-6 py-5 text-right text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/40 text-gray-700">
                            @foreach($completedSessions as $session)
                            <tr class="hover:bg-emerald-100/30 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="text-sm font-black text-gray-800">{{ $session->konseli->nama }}</div>
                                    <div class="text-[10px] font-bold text-gray-500 uppercase">{{ $session->konseli->npm_nip }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-gray-700">{{ $session->started_at?->format('d M Y') }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium">{{ $session->started_at?->format('H:i') }} - {{ $session->ended_at?->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-gray-200/50 text-gray-700 border border-gray-300/50">
                                        <i class="far fa-hourglass mr-1.5"></i>
                                        {{ $session->started_at && $session->ended_at ? $session->started_at->diffForHumans($session->ended_at, true) : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 inline-flex text-[10px] font-black uppercase rounded-full bg-emerald-200/50 text-emerald-800 border border-emerald-200">Selesai</span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('konselor.konseling.detail', $session->id) }}" class="text-emerald-700 hover:text-emerald-900 font-black text-xs transition-colors flex justify-end items-center">
                                        Detail <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-200/30 border-t border-gray-200/50">
                    {{ $completedSessions->appends(request()->query())->links() }}
                </div>
                @else
                <div class="p-10 text-center">
                    <i class="fas fa-search text-3xl text-gray-400 mb-4"></i>
                    <h3 class="font-black text-gray-700">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mt-2">Tidak ada riwayat yang cocok dengan pencarian atau filter yang Anda masukkan.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

<style>
    @keyframes fade-in-down {
        0%   { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes slide-in {
        0%   { opacity: 0; transform: translateY(12px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
    .animate-slide-in     { animation: slide-in 0.35s ease-out forwards; }

    ::-webkit-scrollbar       { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

@endsection

@push('scripts')
<script>
/* ============================================================
   Konstanta & helpers
   ============================================================ */
const KONSELOR_ID = {{ auth()->id() }};
const CSRF_TOKEN  = "{{ csrf_token() }}";

// Helper: Escape HTML
function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// Helper: Format Waktu (Contoh Output: 10:45)
function formatTime(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
}

// ── Template: avatar inner HTML ──────────────────────────────
function avatarHtml(foto, fallbackColorClass, fallbackIconClass) {
    if (foto) {
        // Asumsi 'foto' dari backend sudah berupa path untuk asset storage
        return `<img src="/storage/${foto}" class="w-full h-full object-cover" alt="foto">`; 
    }
    return `<div class="w-full h-full flex items-center justify-center ${fallbackColorClass}">
                <i class="${fallbackIconClass} text-xl"></i>
            </div>`;
}

// ── Template: card permintaan masuk (pending) ─────────────────
function buildPendingCard(s) {
    return `
    <div id="session-${s.id}"
         class="bg-[#f4f7f6] rounded-[2rem] p-6 shadow-lg shadow-gray-200/50 border border-gray-100
                relative overflow-hidden group transition-all hover:shadow-xl flex flex-col animate-slide-in">
        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-100/50 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
        <div class="relative flex-1 flex flex-col">

            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 bg-amber-200/50 rounded-2xl p-0.5 flex-shrink-0">
                    <div class="w-full h-full bg-[#f4f7f6] rounded-[0.9rem] overflow-hidden">
                        ${avatarHtml(s.konseli.foto, 'text-amber-500 bg-gray-50/50', 'fas fa-user')}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-black text-gray-800 text-lg truncate">${escHtml(s.konseli.nama)}</h3>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">${escHtml(s.konseli.npm_nip)}</p>
                </div>
            </div>

            <div class="flex-1"></div>

            <div class="flex gap-3 mt-auto pt-2">
                <form action="${s.routes?.approve || '#'}" method="POST" class="flex-1">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <button type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl transition-all font-black text-sm shadow-md">
                        Terima
                    </button>
                </form>
                <form action="${s.routes?.reject || '#'}" method="POST" class="flex-1">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <button type="submit"
                            class="w-full bg-transparent border-2 border-rose-200 text-rose-500 hover:bg-rose-100 hover:border-rose-300 py-3 rounded-xl transition-all font-black text-sm"
                            onclick="return confirm('Tolak permintaan ini?')">
                        Tolak
                    </button>
                </form>
            </div>
        </div>
    </div>`;
}

// ── Template: card sesi aktif ─────────────────────────────────
function buildActiveCard(s) {
    return `
    <div id="session-${s.id}"
         class="bg-[#f4f7f6] rounded-[2rem] p-6 shadow-lg shadow-gray-200/50 border border-gray-100
                relative overflow-hidden group transition-all hover:shadow-xl flex flex-col animate-slide-in">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-200/20 rounded-full -mr-16 -mt-16"></div>
        <div class="relative flex-1 flex flex-col">

            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-0.5 shadow-md">
                    <div class="w-full h-full bg-[#f4f7f6] rounded-[0.9rem] overflow-hidden">
                        ${avatarHtml(s.konseli.foto, 'text-teal-600', 'fas fa-smile')}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-black text-gray-800 text-lg truncate">${escHtml(s.konseli.nama)}</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest">Sedang Berlangsung</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-200/50 rounded-xl p-3 mb-5 flex justify-between items-center text-sm font-bold text-gray-700 border border-gray-200/50">
                <span class="text-gray-500 uppercase text-[10px] tracking-wider">Mulai:</span>
                <span>${formatTime(s.started_at)} WIB</span>
            </div>

            <div class="flex-1"></div>

            <div class="flex gap-3 mt-auto pt-2">
                <a href="${s.routes?.chat || '#'}"
                   class="flex-[2] bg-gray-800 hover:bg-emerald-700 text-white py-3 rounded-xl transition-all font-black text-sm flex items-center justify-center shadow-md">
                    <i class="fas fa-paper-plane mr-2"></i> Buka Obrolan
                </a>
                <a href="${s.routes?.end_form || '#'}"
                   class="flex-1 bg-transparent border-2 border-gray-300 hover:border-gray-400 text-gray-600 hover:bg-gray-100 py-3 rounded-xl transition-all font-black text-sm flex items-center justify-center">
                    <i class="fas fa-flag-checkered mr-1"></i> Akhiri
                </a>
            </div>
        </div>
    </div>`;
}

const EMPTY_PENDING_HTML = `
    <div id="empty-pending"
         class="bg-[#f4f7f6]/60 border-2 border-dashed border-gray-300 rounded-[2.5rem]
                flex flex-col items-center justify-center p-10 text-center flex-1 min-h-[200px]">
        <i class="fas fa-mug-hot text-3xl text-gray-400 mb-4"></i>
        <h3 class="font-black text-gray-700">Kosong &amp; Tenang</h3>
        <p class="text-sm text-gray-500 font-medium">Belum ada permintaan masuk.</p>
    </div>`;

const EMPTY_ACTIVE_HTML = `
    <div id="empty-active"
         class="bg-[#f4f7f6]/60 border-2 border-dashed border-gray-300 rounded-[2.5rem]
                flex flex-col items-center justify-center p-10 text-center flex-1 min-h-[200px]">
        <i class="fas fa-wind text-3xl text-gray-400 mb-4"></i>
        <h3 class="font-black text-gray-700">Hening...</h3>
        <p class="text-sm text-gray-500 font-medium">Tidak ada sesi aktif saat ini.</p>
    </div>`;

/* ============================================================
   DOM Helpers
   ============================================================ */
const pendingList = document.getElementById('pending-list');
const activeList  = document.getElementById('active-list');

function removeCard(id) {
    const el = document.getElementById(`session-${id}`);
    if (!el) return;
    el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    el.style.opacity    = '0';
    el.style.transform  = 'translateY(-8px)';
    setTimeout(() => el.remove(), 320);
}

function checkEmpty(listEl, emptyHtml, emptyId) {
    setTimeout(() => {
        const cards = listEl.querySelectorAll('[id^="session-"]');
        if (cards.length === 0 && !document.getElementById(emptyId)) {
            listEl.insertAdjacentHTML('beforeend', emptyHtml);
        }
    }, 350);
}

function removeEmpty(listEl) {
    const empty = listEl.querySelector('[id^="empty-"]');
    if (empty) empty.remove();
}

/* ============================================================
   Pusher / Laravel Echo Initialization
   ============================================================ */
// Menunggu DOM dan script Vite selesai dimuat agar window.Echo tidak undefined
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        if (typeof window.Echo !== 'undefined') {
            window.Echo.private(`konselor.${KONSELOR_ID}`)
                .listen('.session.updated', (e) => {
                    const s = e;

                    switch (s.status) {
                        case 'pending':
                            removeEmpty(pendingList);
                            if (!document.getElementById(`session-${s.id}`)) {
                                pendingList.insertAdjacentHTML('afterbegin', buildPendingCard(s));
                            }
                            break;

                        case 'active':
                            removeCard(s.id);
                            checkEmpty(pendingList, EMPTY_PENDING_HTML, 'empty-pending');

                            removeEmpty(activeList);
                            if (!document.getElementById(`session-${s.id}`)) {
                                activeList.insertAdjacentHTML('afterbegin', buildActiveCard(s));
                            }
                            break;

                        case 'rejected':
                        case 'completed':
                            removeCard(s.id);
                            checkEmpty(pendingList, EMPTY_PENDING_HTML, 'empty-pending');
                            checkEmpty(activeList,  EMPTY_ACTIVE_HTML,  'empty-active');
                            break;
                    }
                });
        } else {
            console.warn('Laravel Echo gagal dimuat. Pembaruan real-time tidak akan berjalan.');
        }
    }, 300); // Jeda 300ms untuk memastikan dependencies siap
});
</script>
@endpush