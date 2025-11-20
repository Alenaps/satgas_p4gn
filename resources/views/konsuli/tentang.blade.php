@extends('layouts.konsuli')

@section('title', 'Tentang Kami')

@section('content')
<div class="pt-32 pb-20 bg-gray-100 text-gray-800">

    <!-- Header -->
    <div class="text-center mb-14">
        <h1 class="text-4xl font-extrabold tracking-wide text-slate-800">TENTANG KAMI</h1>
        <p class="text-gray-500 text-sm mt-3">Struktur Organisasi • Visi • Misi • Tujuan</p>
    </div>

    <!-- VISI -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-lg p-12 mb-12 hover:shadow-2xl transition-all duration-300">
        <h2 class="text-center text-2xl font-bold mb-6 text-teal-700 tracking-wide">VISI</h2>
        <p class="text-center text-gray-700 leading-relaxed text-lg font-semibold italic">
            “Menjadi Kampus Bersinar (Bersih dari Narkoba)”
        </p>
    </div>

    <!-- MISI -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-lg p-12 mb-12 hover:shadow-2xl transition-all duration-300">
        <h2 class="text-center text-2xl font-bold mb-6 text-teal-700 tracking-wide">MISI</h2>
        <p class="text-center text-gray-700 text-lg font-semibold leading-relaxed italic">
            “Melakukan screening, edukasi, dan pelayanan dalam upaya P4GN di lingkungan kampus.”
        </p>
    </div>

    <!-- Divider -->
    <div class="max-w-4xl mx-auto my-14">
        <div class="border-t border-gray-300"></div>
    </div>

    <!-- STRUKTUR ORGANISASI -->
    <div class="max-w-5xl mx-auto mb-10">
        <h2 class="text-center text-2xl font-bold mb-10 text-teal-700 tracking-wide">STRUKTUR ORGANISASI</h2>

        <!-- Foto Struktur -->
        <div class="mb-12">
            <img src="/assets/struktur-organisasi.jpeg" 
                 alt="Struktur Organisasi SATGAS P4GN UNILA"
                 class="w-full rounded-3xl shadow-xl border border-gray-200">
        </div>

        <!-- Grid Anggota -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">

            @php
                $anggota = [
                    ['Ketua SATGAS', 'ketua.jpg'],
                    ['Koordinator Pencegahan', 'koordinator-pencegahan.jpg'],
                    ['Koordinator Konseling', 'koordinator-konseling.jpg'],
                    ['Anggota Bidang Pelaporan', 'pelaporan1.jpg'],
                    ['Anggota Dokumentasi', 'dokumentasi.jpg'],
                    ['Tim Media', 'tim-media.jpg'],
                ];
            @endphp

            @foreach($anggota as $a)
            <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <img src="/images/{{ $a[1] }}"
                     class="w-32 h-32 object-cover rounded-full mx-auto mb-5 shadow-md border border-gray-200">
                <h3 class="font-bold text-teal-700 text-lg">{{ $a[0] }}</h3>
                <p class="text-gray-600 text-sm mt-1">Nama Anggota</p>
            </div>
            @endforeach

        </div>
    </div>

</div>
@endsection
