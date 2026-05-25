@extends('layouts.konsuli')

@section('title', 'Laporan Saya')

@section('content')
<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-2xl font-bold text-blue-700 mb-6">
        Laporan Saya
    </h1>

    <div class="grid md:grid-cols-2 gap-6">

        {{-- ================= FORM KLAIM ================= --}}
        <div class="bg-white p-6 rounded-xl shadow border border-blue-100">

            <h2 class="font-semibold text-lg text-blue-700 mb-4">
                Klaim Laporan
            </h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('konsuli.laporan.klaim') }}">
                @csrf

                <input name="kode_laporan"
                    placeholder="Kode Laporan"
                    class="w-full border border-blue-200 rounded px-3 py-2 mb-3 focus:ring-2 focus:ring-blue-400">

                <input name="token_laporan"
                    placeholder="Token Laporan"
                    class="w-full border border-blue-200 rounded px-3 py-2 mb-4 focus:ring-2 focus:ring-blue-400">

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded shadow">
                    Klaim Laporan
                </button>
            </form>
        </div>


        {{-- ================= LIST LAPORAN ================= --}}
        <div class="bg-white p-6 rounded-xl shadow border border-blue-100">

            <h2 class="font-semibold text-lg text-blue-700 mb-4">
                Daftar Laporan
            </h2>

            @forelse($laporans as $laporan)
            <div class="border-b py-3 flex justify-between items-center">

                <div>
                    <p class="font-semibold text-blue-800">
                        {{ $laporan->kode_laporan }}
                    </p>

                    {{-- STATUS BADGE --}}
                    <span class="
                        text-xs px-2 py-1 rounded
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

                <a href="{{ route('konsuli.laporan.show',$laporan->id) }}"
                   class="text-blue-600 hover:underline text-sm font-medium">
                    Lihat Detail →
                </a>

            </div>
            @empty
                <p class="text-gray-500 italic">Belum ada laporan</p>
            @endforelse

        </div>

    </div>

</div>
@endsection