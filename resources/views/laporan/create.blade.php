@extends('layouts.guest')

@section('title', 'Formulir Lapor P4GN')

@push('styles')
<style>
    .card {
        background: #eef2ff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 py-10 px-4">
    <div class="max-w-6xl mx-auto">

        <h2 class="text-center text-xl font-bold mb-6">FORMULIR LAPOR P4GN</h2>

        <!-- GRID FORM -->
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Identitas Pelapor --}}
                <div class="card">
                    <h3 class="font-semibold mb-3">Identitas Pelapor</h3>

                    <label>Nama Pelapor</label>
                    <input type="text" name="nama_pelapor" class="w-full p-2 rounded mb-3">

                    <label>Peran Pelapor</label>
                    <select name="peran_pelapor" class="w-full p-2 rounded mb-3">
                        <option>Mahasiswa</option>
                        <option>Dosen</option>
                        <option>Tendik</option>
                    </select>

                    <label>NIP/NPM</label>
                    <input type="text" name="nip" class="w-full p-2 rounded mb-3">

                    <label>No Telp</label>
                    <input type="text" name="no_telp" class="w-full p-2 rounded mb-3">

                    <label>Email</label>
                    <input type="email" name="email" class="w-full p-2 rounded mb-3">

                    <label>Jenis Kelamin</label>
                    <select name="jk_pelapor" class="w-full p-2 rounded mb-3">
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>

                {{-- Identitas Terlapor --}}
                <div class="card">
                    <h3 class="font-semibold mb-3">Identitas Terlapor</h3>

                    <label>Nama Terlapor</label>
                    <input type="text" name="nama_terlapor" class="w-full p-2 rounded mb-3">

                    <label>Peran Terlapor</label>
                    <select name="peran_terlapor" class="w-full p-2 rounded mb-3">
                        <option>Mahasiswa</option>
                        <option>Dosen</option>
                        <option>Tendik</option>
                    </select>

                    <label>No Telp</label>
                    <input type="text" name="no_telp_terlapor" class="w-full p-2 rounded mb-3">

                    <label>Jenis Kelamin</label>
                    <select name="jk_terlapor" class="w-full p-2 rounded mb-3">
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>

                    <label>Alamat Terlapor</label>
                    <input type="text" name="alamat_terlapor" class="w-full p-2 rounded mb-3">
                </div>
            </div>

            {{-- Kejadian --}}
            <div class="card mt-6">
                <h3 class="font-semibold mb-3">Kejadian</h3>

                <label>Lokasi Kejadian</label>
                <input type="text" name="lokasi" class="w-full p-2 rounded mb-3">

                <label>Upload Foto Lokasi</label>
                <input type="file" name="foto_lokasi" class="w-full p-2 rounded mb-3">

                <label>Tanggal Kejadian</label>
                <input type="date" name="tanggal" class="w-full p-2 rounded mb-3">

                <label>Jenis Narkoba</label>
                <input type="text" name="jenis_narkoba" class="w-full p-2 rounded mb-3">

                <label>Uraian Kronologi</label>
                <textarea name="kronologi" class="w-full p-2 rounded mb-3"></textarea>
            </div>

            <div class="flex justify-end mt-5">
                <button class="px-6 py-3 bg-blue-600 text-white rounded-full">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
