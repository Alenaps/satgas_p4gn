@extends('layouts.admin')

@section('title', 'Detail Laporan')

@section('content')
<div class="p-8">

    <h1 class="text-3xl font-bold mb-8 text-blue-800 tracking-wide">
        DETAIL LAPORAN
    </h1>

    <!-- ==== CARD COMPONENT ==== -->
    @php
        $cardClass = "bg-white border border-blue-200 shadow-md rounded-xl p-6 mb-8";
        $sectionTitle = "text-xl font-semibold text-blue-700 mb-4 pb-1 border-b border-blue-300";
        $labelClass = "font-semibold text-blue-800";
        $valueClass = "bg-blue-50 p-3 rounded border border-blue-100";
    @endphp

    <!-- INFORMASI PELAPOR -->
    <div class="{{ $cardClass }}">
        <h2 class="{{ $sectionTitle }}">Informasi Pelapor</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="{{ $labelClass }}">Nama Pelapor</label>
                <div class="{{ $valueClass }}">{{ $laporan->nama_pelapor }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">NIP/NPM</label>
                <div class="{{ $valueClass }}">{{ $laporan->npm_nip ?? '-' }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Jenis Kelamin</label>
                <div class="{{ $valueClass }}">{{ $laporan->jk_pelapor }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">No Telp</label>
                <div class="{{ $valueClass }}">{{ $laporan->telp_pelapor ?? '-' }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Peran Pelapor</label>
                <div class="{{ $valueClass }}">{{ $laporan->peran_pelapor }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Email</label>
                <div class="{{ $valueClass }}">{{ $laporan->email ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- INFORMASI TERLAPOR -->
    <div class="{{ $cardClass }}">
        <h2 class="{{ $sectionTitle }}">Informasi Terlapor</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="{{ $labelClass }}">Nama Terlapor</label>
                <div class="{{ $valueClass }}">{{ $laporan->nama_terlapor }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">No Telp</label>
                <div class="{{ $valueClass }}">{{ $laporan->telp_terlapor ?? '-' }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Peran Terlapor</label>
                <div class="{{ $valueClass }}">{{ $laporan->peran_terlapor }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Alamat Terlapor</label>
                <div class="{{ $valueClass }}">{{ $laporan->alamat_terlapor ?? '-' }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Jenis Kelamin</label>
                <div class="{{ $valueClass }}">{{ $laporan->jk_terlapor }}</div>
            </div>

            <div>
                <label class="{{ $labelClass }}">Jenis Kasus/Indikasi</label>
                <div class="{{ $valueClass }}">{{ $laporan->jenis_kasus ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- INFORMASI KEJADIAN -->
    <div class="{{ $cardClass }}">
        <h2 class="{{ $sectionTitle }}">Informasi Kejadian</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- KOLOM KIRI: Lokasi, Jenis, Tanggal -->
            <div class="flex flex-col gap-6">

                <div>
                    <label class="{{ $labelClass }}">Lokasi Kejadian</label>
                    <div class="{{ $valueClass }}">{{ $laporan->lokasi }}</div>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Jenis Narkoba</label>
                    <div class="{{ $valueClass }}">{{ $laporan->jenis_narkoba ?? '-' }}</div>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Tanggal Kejadian</label>
                    <div class="{{ $valueClass }}">
                        {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: FOTO -->
            <div>
                <label class="{{ $labelClass }}">Foto Lokasi</label>

                @if ($laporan->foto_lokasi)
                    <img src="{{ asset('storage/' . $laporan->foto_lokasi) }}"
                        class="w-full max-w-sm rounded-lg border border-blue-200 shadow-md mt-2 mx-auto">
                @else
                    <div class="{{ $valueClass }}">Tidak ada foto</div>
                @endif
            </div>

            <!-- KRONOLOGI FULL WIDTH -->
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}">Uraian Kronologi</label>
                <div class="{{ $valueClass }} leading-relaxed">
                    {{ $laporan->kronologi ?? '-' }}
                </div>
            </div>

        </div>
    </div>


</div>
@endsection
