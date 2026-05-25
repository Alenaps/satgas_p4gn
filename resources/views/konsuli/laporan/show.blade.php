@extends('layouts.konsuli')

@section('title', 'Detail Laporan')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    {{-- ================= HEADER ================= --}}
    <div class="bg-white p-6 rounded-xl shadow border border-blue-100 mb-6">

        <div class="flex justify-between items-center flex-wrap gap-3">

            <div>
                <h2 class="text-xl font-bold text-blue-700">
                    {{ $laporan->kode_laporan }}
                </h2>
                <p class="text-sm text-gray-500">
                    Dibuat: {{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y H:i') }}
                </p>
            </div>

            {{-- STATUS BADGE --}}
            <span class="
                text-sm px-4 py-1 rounded-full font-semibold
                @if($laporan->status=='terkirim') bg-yellow-100 text-yellow-700
                @elseif($laporan->status=='diverifikasi') bg-blue-100 text-blue-700
                @elseif($laporan->status=='diproses') bg-purple-100 text-purple-700
                @elseif($laporan->status=='selesai') bg-green-100 text-green-700
                @elseif($laporan->status=='ditolak') bg-red-100 text-red-700
                @endif
            ">
                {{ strtoupper($laporan->status) }}
            </span>

        </div>

    </div>


    {{-- ================= TIMELINE ================= --}}
    <div class="bg-white p-6 rounded-xl shadow border border-blue-100">

        <h3 class="font-semibold text-blue-700 mb-6 text-lg">
            Lihat Status Laporan
        </h3>

        <div class="relative border-l-4 border-blue-400 pl-6">

            @forelse($laporan->tindakLanjuts as $t)
            <div class="mb-8 relative">

                {{-- TITIK WARNA SESUAI STATUS --}}
                <div class="
                    absolute -left-[10px] top-1 w-5 h-5 rounded-full border-4 border-white
                    @if($t->status=='terkirim') bg-yellow-500
                    @elseif($t->status=='diverifikasi') bg-blue-500
                    @elseif($t->status=='diproses') bg-purple-500
                    @elseif($t->status=='selesai') bg-green-500
                    @elseif($t->status=='ditolak') bg-red-500
                    @endif
                "></div>

                {{-- STATUS --}}
                <p class="font-semibold text-blue-700 pl-3">
                    {{ strtoupper($t->status) }}
                </p>

                {{-- CATATAN --}}
                <p class="text-gray-700 leading-relaxed pl-3">
                    {{ $t->catatan ?? '-' }}
                </p>

                {{-- WAKTU --}}
                <p class="text-xs text-gray-400 mt-1 pl-3">
                    {{ \Carbon\Carbon::parse($t->created_at)->format('d M Y • H:i') }}
                </p>

            </div>
            @empty
                <p class="text-gray-500 italic">
                    Belum ada tindak lanjut dari admin.
                </p>
            @endforelse

        </div>

    </div>

</div>
@endsection