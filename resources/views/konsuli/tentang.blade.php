@extends('layouts.konsuli')

@section('title', 'Tentang Kami')

@section('content')

<div class="bg-gray-100 text-gray-800 ">

    {{-- Hero Background --}}
    <div class="relative w-full h-96 bg-cover bg-center flex items-center justify-center bg-[url('{{ asset('assets/struktur-organisasi.jpeg') }}')]">

        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Header -->
        <div class="relative text-center px-4">
            <h1 class="text-white text-4xl font-extrabold tracking-wide">TENTANG KAMI</h1>
            <p class="text-gray-200 text-sm mt-3">Struktur Organisasi • Visi • Misi • Tujuan</p>
        </div>
    </div>


    <!-- VISI -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-lg p-12 mt-16 mb-12 hover:shadow-2xl transition-all duration-300">
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
    <div class="max-w-5xl mx-auto pb-12">
        <h2 class="text-center text-2xl font-bold mb-10 text-teal-700 tracking-wide">STRUKTUR ORGANISASI</h2>

        <!-- Grid Anggota -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">

            @php
                $anggota = [
                    ['Ketua SATGAS', 'profile.jpg'],
                    ['Sekretaris', 'profile.jpg'],
                    ['Koordinator Pencegahan', 'profile.jpg'],
                    ['Koordinator Pelaporan', 'profile.jpg'],
                    ['Koordinator Konseling', 'profile.jpg'],
                    ['Koordinator Dokumentasi', 'profile.jpg'],
                ];
            @endphp

            @foreach($anggota as $a)
            <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <img src="{{ asset('assets/' . $a[1]) }}"
                     class="w-32 h-32 object-cover rounded-full mx-auto mb-5 shadow-md border border-gray-200"
                     alt="{{ $a[0] }}">
                <h3 class="font-bold text-teal-700 text-lg">{{ $a[0] }}</h3>
                <p class="text-gray-600 text-sm mt-1">Nama Anggota</p>
            </div>
            @endforeach

        </div>
    </div>

</div>
@endsection
