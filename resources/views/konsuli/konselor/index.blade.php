@extends('layouts.konsuli')

@section('title', 'Daftar Konselor')

@section('content')
<div class="min-h-screen bg-[#0f172a] pb-32 font-sans overflow-x-hidden relative">

    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[120px] -z-10 -mr-64 -mt-64"></div>
    <div class="absolute bottom-1/4 left-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[100px] -z-10 -ml-48"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">

        {{-- Header --}}
        <div class="mb-20 text-center max-w-3xl mx-auto animate-fade-in">
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 rounded-full bg-emerald-500/10 backdrop-blur-md border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em]">
                <i class="fas fa-heart animate-pulse"></i> Welcoming Space
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-8 tracking-tight leading-[1.1]">
                Temukan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Teman Cerita</span> yang Paling Pas
            </h1>
            <p class="text-lg text-slate-400 leading-relaxed font-medium px-4">
                Pilih konselor yang membuatmu merasa paling nyaman. Obrolanmu bersifat privat, aman, dan tanpa penghakiman di ruang aman ini.
            </p>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="max-w-2xl mx-auto bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-xl text-emerald-400 px-6 py-4 rounded-3xl mb-12 flex items-center shadow-2xl animate-fade-in">
            <div class="bg-emerald-500 text-white p-2 rounded-full mr-4 text-xs"><i class="fas fa-check"></i></div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-2xl mx-auto bg-rose-500/10 border border-rose-500/20 backdrop-blur-xl text-rose-400 px-6 py-4 rounded-3xl mb-12 flex items-center shadow-2xl animate-fade-in">
            <div class="bg-rose-500 text-white p-2 rounded-full mr-4 text-xs"><i class="fas fa-times"></i></div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Grid Konselor --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            @forelse($konselors as $konselor)
            @php
                $profile          = $konselor->konselorProfile;
                $hasActiveSession = $sessions->where('konselor_id', $konselor->id)
                                             ->whereIn('status', ['pending', 'active'])
                                             ->first();
                $isGloballyBusy   = in_array($konselor->id, $busyKonselorIds ?? []);
            @endphp

            {{-- Card --}}
            <div id="konselor-card-{{ $konselor->id }}"
                 class="bg-white rounded-3xl shadow-2xl shadow-black/20 hover:shadow-emerald-500/10 transition-all duration-500 border border-white/5 flex flex-col sm:flex-row overflow-hidden group">

                {{-- Kolom Kiri: Avatar --}}
                <div class="flex-shrink-0 w-full sm:w-40 bg-slate-50 flex flex-col items-center justify-center p-6 relative">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-emerald-50 to-slate-50 opacity-60"></div>

                    <div class="relative z-10 w-24 h-24 sm:w-32 sm:h-32 p-1 rounded-2xl bg-gradient-to-tr from-emerald-400 to-blue-500 shadow-lg group-hover:scale-105 group-hover:rotate-3 transition-all duration-500">
                        <div class="w-full h-full rounded-xl bg-white p-0.5 overflow-hidden">
                            @if($konselor->foto)
                                <img src="{{ asset('storage/' . $konselor->foto) }}" class="w-full h-full object-cover rounded-[10px]" alt="{{ $konselor->nama }}">
                            @else
                                <div class="w-full h-full bg-slate-100 rounded-[10px] flex items-center justify-center text-slate-300">
                                    <i class="fas fa-user-circle text-5xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Status dot di sudut avatar --}}
                        <div id="konselor-dot-{{ $konselor->id }}"
                             class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-sm
                                    {{ $isGloballyBusy ? 'bg-rose-500' : 'bg-emerald-500' }}">
                        </div>
                    </div>

                    @if($profile && isset($profile->sertifikasi_P4GN) && $profile->sertifikasi_P4GN)
                    <div class="relative z-10 mt-5 flex flex-col items-center gap-1">
                        <i class="fas fa-certificate text-emerald-500 text-base"></i>
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-wide text-center leading-tight">P4GN Certified</span>
                    </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Info --}}
                <div class="flex-grow p-6 flex flex-col justify-between min-w-0">

                    {{-- Nama & Status Badge --}}
                    <div class="mb-2">
                        <div class="flex items-center gap-2 flex-wrap mb-1 min-w-0">
                            <h3 class="font-black text-xl text-slate-900 leading-tight group-hover:text-emerald-600 transition-colors truncate">{{ $konselor->nama }}</h3>
                            <span id="konselor-status-{{ $konselor->id }}"
                                  class="flex-shrink-0 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border
                                         {{ $isGloballyBusy ? 'bg-rose-50 text-rose-500 border-rose-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200' }}">
                                {{ $isGloballyBusy ? 'Sedang Sibuk' : 'Tersedia' }}
                            </span>
                        </div>
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">ID: {{ $konselor->npm_nip }}</span>
                    </div>

                    {{-- Instansi & Jabatan --}}
                    @if($profile && ($profile->instansi?->nama_instansi || $profile->jabatan?->nama_jabatan))
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if($profile->instansi?->nama_instansi)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase tracking-wider rounded-lg">
                            <i class="fas fa-building text-emerald-500 text-[9px]"></i>
                            {{ $profile->instansi->nama_instansi }}
                        </span>
                        @endif
                        @if($profile->jabatan?->nama_jabatan)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-[10px] font-black uppercase tracking-wider rounded-lg">
                            <i class="fas fa-id-card text-blue-500 text-[9px]"></i>
                            {{ $profile->jabatan->nama_jabatan }}
                        </span>
                        @endif
                    </div>
                    @else
                    <div class="mb-4"></div>
                    @endif

                    {{-- Info Grid --}}
                    @if($profile)
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        @if($profile->spesialisasi)
                        <div class="bg-slate-50 rounded-xl px-3 py-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Spesialisasi</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $profile->spesialisasi }}</p>
                        </div>
                        @endif
                        @if($profile->pendidikan_terakhir)
                        <div class="bg-slate-50 rounded-xl px-3 py-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Pendidikan Terakhir</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $profile->pendidikan_terakhir }}</p>
                        </div>
                        @endif
                        @if($profile->pengalaman_kerja)
                        <div class="bg-slate-50 rounded-xl px-3 py-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Pengalaman Kerja</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $profile->pengalaman_kerja }} Tahun</p>
                        </div>
                        @endif
                        @if($profile->nomor_lisensi)
                        <div class="bg-slate-50 rounded-xl px-3 py-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">No. Lisensi</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $profile->nomor_lisensi }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Bio --}}
                    <div class="mb-5">
                        <p class="text-sm text-slate-500 italic leading-relaxed">
                            {{ $profile->bio_singkat ?? 'Siap menjadi pendengar setia dan membantumu menemukan jalan keluar terbaik.' }}
                        </p>
                    </div>

                    {{-- Action --}}
                    <div id="konselor-action-{{ $konselor->id }}" class="mt-auto">
                        @if($hasActiveSession)
                            @if($hasActiveSession->status === 'pending')
                            <div class="bg-amber-50 rounded-xl py-3 flex items-center justify-center gap-2 border border-amber-100">
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                </span>
                                <span class="text-xs font-black text-amber-700 uppercase tracking-widest">Menunggu Konfirmasi</span>
                            </div>
                            @else
                            <a href="{{ route('konsuli.konseling.chat', $hasActiveSession->id) }}"
                               class="flex items-center justify-center w-full bg-slate-900 hover:bg-emerald-600 text-white py-3 rounded-xl transition-all duration-300 font-black text-xs uppercase tracking-widest">
                                <i class="fas fa-comment-dots mr-2"></i> Lanjutkan Obrolan
                            </a>
                            @endif
                        @elseif($isGloballyBusy)
                            <div class="bg-slate-50 rounded-xl py-3 flex items-center justify-center gap-2 border border-slate-200 cursor-not-allowed">
                                <i class="fas fa-user-clock text-slate-400 text-xs"></i>
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Konselor Sedang Sibuk</span>
                            </div>
                        @else
                            <form action="{{ route('konsuli.konseling.request') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="konselor_id" value="{{ $konselor->id }}">
                                <button type="submit"
                                        class="group/btn w-full bg-white border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white py-3 rounded-xl transition-all duration-300 font-black text-xs uppercase tracking-widest flex items-center justify-center">
                                    Mulai Cerita <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition-transform"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @empty
            <div class="col-span-full bg-white/5 border border-white/10 rounded-[3rem] p-20 text-center backdrop-blur-sm">
                <div class="w-24 h-24 bg-white/10 rounded-[2rem] flex items-center justify-center mx-auto mb-8 rotate-12">
                    <i class="fas fa-moon text-slate-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-4">Lagi Istirahat Sejenak</h3>
                <p class="text-slate-400 font-medium max-w-md mx-auto">Saat ini belum ada konselor yang tersedia. Silakan mampir lagi nanti ya!</p>
            </div>
            @endforelse
        </div>

        {{-- Help Section --}}
        @if($konselors->count() > 0)
        <div class="mt-32 max-w-5xl mx-auto">
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[4rem] p-12 md:p-16 text-white relative overflow-hidden shadow-2xl shadow-emerald-500/20">
                <i class="fas fa-shield-alt absolute -bottom-10 -right-10 text-[200px] opacity-10 rotate-12"></i>
                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                    <div class="lg:w-1/3 text-center lg:text-left">
                        <div class="inline-block p-4 bg-white/20 rounded-3xl mb-6 backdrop-blur-md">
                            <i class="fas fa-star text-4xl text-yellow-300"></i>
                        </div>
                        <h3 class="text-4xl font-black mb-4 leading-tight">Siap Melangkah?</h3>
                        <p class="text-emerald-50 font-medium">Hanya perlu langkah sederhana untuk mulai merasa lebih tenang.</p>
                    </div>
                    <div class="lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-10">
                        <div class="flex gap-5">
                            <span class="text-5xl font-black text-white/20">01</span>
                            <div>
                                <h4 class="font-black text-white mb-2 uppercase text-xs tracking-widest">Pilih Sahabat</h4>
                                <p class="text-sm text-emerald-50/80 leading-relaxed">Cari profil konselor yang paling klik di hati kamu.</p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <span class="text-5xl font-black text-white/20">02</span>
                            <div>
                                <h4 class="font-black text-white mb-2 uppercase text-xs tracking-widest">Kirim Sapaan</h4>
                                <p class="text-sm text-emerald-50/80 leading-relaxed">Klik tombol request untuk meminta waktu bercerita.</p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <span class="text-5xl font-black text-white/20">03</span>
                            <div>
                                <h4 class="font-black text-white mb-2 uppercase text-xs tracking-widest">Tunggu Sapaan</h4>
                                <p class="text-sm text-emerald-50/80 leading-relaxed">Konselor akan segera membalas permintaanmu secepatnya.</p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <span class="text-5xl font-black text-white/20">04</span>
                            <div>
                                <h4 class="font-black text-white mb-2 uppercase text-xs tracking-widest">Ruang Aman</h4>
                                <p class="text-sm text-emerald-50/80 leading-relaxed">Mulai diskusi apapun dengan aman tanpa rasa cemas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection

@push('scripts')
<script>
const CSRF_TOKEN_KL  = "{{ csrf_token() }}";
const REQUEST_URL    = "{{ route('konsuli.konseling.request') }}";
const CHAT_BASE_URL  = "{{ url('konsuli/konseling') }}";
const MY_KONSELI_ID  = {{ auth()->id() }};

/* ── Update status badge & dot ──────────────────────── */
function setKonselorAvailability(konselorId, isBusy) {
    const badge = document.getElementById(`konselor-status-${konselorId}`);
    const dot   = document.getElementById(`konselor-dot-${konselorId}`);

    if (badge) {
        badge.textContent = isBusy ? 'Sedang Sibuk' : 'Tersedia';
        badge.className   = `flex-shrink-0 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border ${
            isBusy
                ? 'bg-rose-50 text-rose-500 border-rose-200'
                : 'bg-emerald-50 text-emerald-600 border-emerald-200'
        }`;
    }

    if (dot) {
        dot.className = `absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-sm ${
            isBusy ? 'bg-rose-500' : 'bg-emerald-500'
        }`;
    }
}

/* ── Update action button ───────────────────────────── */
function setActionBusy(konselorId) {
    const div = document.getElementById(`konselor-action-${konselorId}`);
    if (!div) return;
    div.innerHTML = `
        <div class="bg-slate-50 rounded-xl py-3 flex items-center justify-center gap-2 border border-slate-200 cursor-not-allowed">
            <i class="fas fa-user-clock text-slate-400 text-xs"></i>
            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Konselor Sedang Sibuk</span>
        </div>`;
}

function setActionAvailable(konselorId) {
    const div = document.getElementById(`konselor-action-${konselorId}`);
    if (!div) return;
    div.innerHTML = `
        <form action="${REQUEST_URL}" method="POST" class="w-full">
            <input type="hidden" name="_token" value="${CSRF_TOKEN_KL}">
            <input type="hidden" name="konselor_id" value="${konselorId}">
            <button type="submit"
                    class="group/btn w-full bg-white border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white py-3 rounded-xl transition-all duration-300 font-black text-xs uppercase tracking-widest flex items-center justify-center">
                Mulai Cerita <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition-transform"></i>
            </button>
        </form>`;
}

function setActionPending() {
    return `
        <div class="bg-amber-50 rounded-xl py-3 flex items-center justify-center gap-2 border border-amber-100">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
            <span class="text-xs font-black text-amber-700 uppercase tracking-widest">Menunggu Konfirmasi</span>
        </div>`;
}

function setActionActive(konselorId, sessionId) {
    const div = document.getElementById(`konselor-action-${konselorId}`);
    if (!div) return;
    div.innerHTML = `
        <a href="${CHAT_BASE_URL}/${sessionId}/chat"
           class="flex items-center justify-center w-full bg-slate-900 hover:bg-emerald-600 text-white py-3 rounded-xl transition-all duration-300 font-black text-xs uppercase tracking-widest">
            <i class="fas fa-comment-dots mr-2"></i> Lanjutkan Obrolan
        </a>`;
}

/* ── Echo Listener ──────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        if (typeof window.Echo === 'undefined') {
            console.warn('Laravel Echo tidak tersedia.');
            return;
        }

        window.Echo.channel('konselor-availability')
            .listen('.status.updated', (e) => {
                setKonselorAvailability(e.konselor_id, e.availability === 'busy');

                if (e.availability === 'available') {
                    const actionDiv = document.getElementById(`konselor-action-${e.konselor_id}`);
                    if (actionDiv && actionDiv.querySelector('.fa-user-clock')) {
                        setActionAvailable(e.konselor_id);
                    }
                } else {
                    const actionDiv = document.getElementById(`konselor-action-${e.konselor_id}`);
                    if (actionDiv && actionDiv.querySelector('form button[type="submit"]')) {
                        setActionBusy(e.konselor_id);
                    }
                }
            });

        window.Echo.private(`konseli.${MY_KONSELI_ID}`)
            .listen('.session.updated', (s) => {
                const konselorId = s.konselor?.id;
                if (!konselorId) return;

                if (s.status === 'active') {
                    setActionActive(konselorId, s.id);
                } else if (s.status === 'rejected') {
                    const actionDiv = document.getElementById(`konselor-action-${konselorId}`);
                    if (actionDiv) setActionAvailable(konselorId);
                } else if (s.status === 'completed') {
                    setTimeout(() => window.location.reload(), 600);
                }
            });

    }, 300);
});
</script>
@endpush