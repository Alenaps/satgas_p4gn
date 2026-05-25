@extends('layouts.konsuli')

@section('title', 'Laporan - SATGAS P4GN UNILA')

@section('content')
<div class="min-h-screen bg-gray-100 py-10 px-4">
    <div class="max-w-3xl mx-auto">

        {{-- ALERT --}}
        <div class="bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl mb-6 shadow">
            <strong>Terima Kasih!</strong>  
            <p class="mt-1">Laporan atau data yang Anda kirimkan telah diterima dengan baik.</p>
        </div>

        {{-- CARD INFORMASI --}}
        <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-200">
            
            <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">
                Informasi Keamanan Data
            </h1>

            <p class="text-gray-700 leading-relaxed mb-4">
                Kami mengucapkan terima kasih atas partisipasi Anda dalam menyampaikan laporan melalui sistem ini.
                Seluruh data yang Anda kirimkan <span class="font-semibold">diproses secara rahasia,
                aman, dan hanya digunakan untuk keperluan penanganan serta tindak lanjut sesuai ketentuan
                Satuan Tugas P4GN Universitas Lampung.</span>
            </p>

            <p class="text-gray-700 leading-relaxed mb-4">
                Setiap laporan yang diterima akan diverifikasi oleh tim konselor dan petugas terkait. 
                Identitas pelapor tidak akan disebarluaskan dan tetap terjaga sesuai standar kerahasiaan informasi.
                Pantau perkembangan laporan Anda di menu <span class="font-semibold">"Laporan Saya"</span>.
            </p>

            <p class="text-gray-700 leading-relaxed">
                Terima kasih telah berkontribusi dalam upaya pencegahan dan penanganan penyalahgunaan narkoba
                di lingkungan Universitas Lampung.  
                <span class="font-semibold">Bersama kita wujudkan lingkungan kampus yang sehat dan aman.</span>
            </p>

            {{-- FOOTER UCAPAN --}}
            <div class="text-center mt-8">
                <p class="text-gray-800 font-semibold">Salam Hormat,</p>
                <p class="text-green-700 font-bold text-lg">SATGAS P4GN Universitas Lampung</p>
            </div>
        </div>

    </div>
</div>
@endsection
