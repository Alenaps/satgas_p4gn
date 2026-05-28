@extends('layouts.admin')

@section('title', 'Dashboard Admin')

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
                <i class="fas fa-gauge-high opacity-80"></i> Dashboard Admin
            </h1>
            <p class="text-white/60 mt-2 text-sm">
                Pantauan sistem SatgasP4GN — ringkasan hari ini
            </p>
        </div>
        <div class="text-white/40 text-xs text-right">
            <i class="fas fa-calendar mr-1"></i> {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>
</div>

{{-- ══ MINI SCORECARD ══ --}}
<p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">
    <i class="fas fa-bolt text-yellow-400 mr-1"></i> Indikator Cepat Hari Ini
</p>
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    {{-- Laporan Menunggu --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up
                relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-red-500 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Laporan Menunggu</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1"
               style="font-family:'JetBrains Mono',monospace;">{{ $laporanMenunggu }}</p>
            <p class="text-xs text-slate-400 mt-1">menunggu verifikasi</p>
        </div>
        @if($laporanMenunggu > 0)
        <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-red-400 rounded-full animate-ping"></span>
        @endif
    </div>

    {{-- Total Laporan --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up
                relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-green-500 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-green-50 text-green-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Total Laporan</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1"
               style="font-family:'JetBrains Mono',monospace;">{{ $totalLaporan }}</p>
            <p class="text-xs text-slate-400 mt-1">semua laporan masuk</p>
        </div>
        @if($totalLaporan > 0)
        <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-green-400 rounded-full animate-ping"></span>
        @endif
    </div>

    {{-- Sesi Aktif Hari Ini --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up
                relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-blue-500 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-circle-dot"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Sesi Aktif</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1"
               style="font-family:'JetBrains Mono',monospace;">{{ $sesiAktifHariIni }}</p>
            <p class="text-xs text-slate-400 mt-1">berjalan hari ini</p>
        </div>
    </div>

    {{-- Pengguna Baru --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4
                hover:-translate-y-1 hover:shadow-xl transition-all duration-200 fade-up
                relative overflow-hidden
                before:absolute before:inset-x-0 before:top-0 before:h-1 before:bg-emerald-500 before:rounded-t-2xl">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-user-plus"></i>
        </div>
        <div>
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-widest">Pengguna Baru</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1"
               style="font-family:'JetBrains Mono',monospace;">{{ $penggunaBaru }}</p>
            <p class="text-xs text-slate-400 mt-1">mendaftar hari ini</p>
        </div>
    </div>

</div>

{{-- ══ ROW 2: TABEL LAPORAN + LOG AKTIVITAS ══ --}}
<div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

    {{-- Tabel Laporan Masuk (3/5) --}}
    <div class="xl:col-span-3 bg-white border border-slate-200 rounded-2xl p-6 fade-up">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">
                <i class="fas fa-file-circle-exclamation text-red-400 mr-1"></i> Laporan Masuk Terkini
            </p>
            <a href="{{ route('admin.laporan.index') }}"
               class="text-xs text-indigo-500 font-semibold hover:underline">
                Lihat semua <i class="fas fa-arrow-right ml-0.5"></i>
            </a>
        </div>

        @if($laporanTerkini->isEmpty())
        <div class="text-center py-12 text-slate-300">
            <i class="fas fa-inbox text-4xl mb-3 block opacity-40"></i>
            <p class="text-sm">Tidak ada laporan dengan status terkirim / diproses</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[0.68rem] font-bold uppercase tracking-widest">
                        <th class="px-3 py-3 rounded-l-lg">Tanggal</th>
                        <th class="px-3 py-3">Pelapor</th>
                        <th class="px-3 py-3">Jenis Kasus</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3 rounded-r-lg text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($laporanTerkini as $lap)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3 py-3.5 text-slate-400 whitespace-nowrap text-xs"
                            style="font-family:'JetBrains Mono',monospace;">
                            {{ \Carbon\Carbon::parse($lap->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-3 py-3.5 font-semibold text-slate-700">
                            {{ $lap->nama_pelapor }}
                        </td>
                        <td class="px-3 py-3.5">
                            @php
                                $jenisColor = match(strtolower($lap->jenis_kasus ?? '')) {
                                    'pengedar' => 'bg-red-100 text-red-700',
                                    'pengguna' => 'bg-amber-100 text-amber-700',
                                    default    => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[0.68rem] font-bold uppercase {{ $jenisColor }}">
                                {{ $lap->jenis_kasus ?? '—' }}
                            </span>
                        </td>
                        <td class="px-3 py-3.5">
                            @php
                                $statusColor = match($lap->status) {
                                    'terkirim' => 'bg-yellow-100 text-yellow-700',
                                    'diproses' => 'bg-blue-100 text-blue-700',
                                    default    => 'bg-slate-100 text-slate-500',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[0.68rem] font-bold capitalize {{ $statusColor }}">
                                {{ $lap->status }}
                            </span>
                        </td>
                        <td class="px-3 py-3.5 text-center">
                            <a href="{{ route('admin.laporan.show', $lap->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                      bg-indigo-50 text-indigo-600 text-xs font-bold
                                      hover:bg-indigo-100 transition-colors">
                                <i class="fas fa-arrow-up-right-from-square text-[0.6rem]"></i>
                                Tindak Lanjut
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Log Aktivitas (2/5) --}}
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 fade-up">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-5 pb-3 border-b border-slate-100">
            <i class="fas fa-rss text-indigo-400 mr-1"></i> Log Aktivitas Sistem
        </p>

        @if($logAktivitas->isEmpty())
        <div class="text-center py-12 text-slate-300">
            <i class="fas fa-satellite-dish text-4xl mb-3 block opacity-40"></i>
            <p class="text-sm">Belum ada aktivitas tercatat</p>
        </div>
        @else
        <div class="relative">
            {{-- Garis timeline --}}
            <div class="absolute left-[17px] top-2 bottom-2 w-px bg-slate-100"></div>

            <div class="space-y-4">
                @foreach($logAktivitas as $log)
                <div class="flex gap-3 relative">
                    {{-- Dot --}}
                    @if($log->tipe === 'sesi')
                        @php
                            $dotColor = match($log->status) {
                                'completed' => 'bg-emerald-400',
                                'active'    => 'bg-blue-400',
                                'rejected'  => 'bg-red-400',
                                default     => 'bg-yellow-400',
                            };
                        @endphp
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 z-10
                                    {{ str_replace('bg-', 'bg-', $dotColor) }} bg-opacity-15 border border-current"
                             style="color: {{ $dotColor === 'bg-emerald-400' ? '#10b981' : ($dotColor === 'bg-blue-400' ? '#3b82f6' : ($dotColor === 'bg-red-400' ? '#ef4444' : '#f59e0b')) }}">
                            <i class="fas {{ $log->status === 'completed' ? 'fa-circle-check' : ($log->status === 'active' ? 'fa-circle-dot' : ($log->status === 'rejected' ? 'fa-circle-xmark' : 'fa-clock')) }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <p class="text-sm text-slate-700 leading-snug">
                                @if($log->status === 'completed')
                                    <span class="font-semibold">{{ $log->nama_konselor }}</span>
                                    menyelesaikan sesi dengan
                                    <span class="font-semibold">{{ $log->sivitas_konseli }}</span>
                                @elseif($log->status === 'active')
                                    Sesi konseling
                                    <span class="font-semibold">{{ $log->nama_konselor }}</span>
                                    sedang berjalan
                                @elseif($log->status === 'rejected')
                                    <span class="font-semibold">{{ $log->nama_konselor }}</span>
                                    menolak permintaan sesi
                                @else
                                    Permintaan konseling baru menunggu konselor
                                @endif
                            </p>
                            <p class="text-[0.68rem] text-slate-400 mt-0.5">
                                <i class="fas fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($log->waktu)->diffForHumans() }}
                            </p>
                        </div>
                    @else
                        <div class="w-9 h-9 rounded-full bg-purple-50 border border-purple-200 flex items-center justify-center shrink-0 z-10 text-purple-500">
                            <i class="fas fa-user-plus text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <p class="text-sm text-slate-700 leading-snug">
                                Pengguna baru
                                <span class="font-semibold">{{ $log->nama }}</span>
                                mendaftar dari
                                <span class="font-semibold">{{ $log->nama_unit }}</span>
                            </p>
                            <p class="text-[0.68rem] text-slate-400 mt-0.5">
                                <i class="fas fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($log->waktu)->diffForHumans() }}
                            </p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>

</div>
@endsection