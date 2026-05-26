@extends('layouts.konsuli')

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
        {{-- Catatan P4GN --}}
        <div class="rounded-xl border border-gray-200 overflow-hidden mb-8">

            <div class="flex items-center gap-3 px-6 py-4" style="background-color: #1E90FF;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L2 7l10 5 10-5-10-5z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
                <p class="text-white font-semibold text-base m-0">Formulir Lapor P4GN</p>
            </div>

            <div class="px-6 py-5">
                <p class="text-gray-700 text-sm leading-relaxed mb-4">
                    Bersama kita wujudkan lingkungan kampus yang bersih dari narkoba melalui Gerakan
                    <span class="font-semibold" style="color: #0202c7;">P4GN</span> —
                    Pencegahan, Pemberantasan, Penyalahgunaan, dan Peredaran Gelap Narkotika.
                </p>
                <div class="flex items-start gap-3 rounded-lg p-3" style="background-color: #F5F4FE;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="flex-shrink-0 mt-0.5">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="#1E90FF"/>
                    </svg>
                    <p class="text-sm leading-relaxed m-0" style="color: #000;">
                        Pastikan data yang Anda isi sudah benar dan sesuai fakta. Seluruh informasi yang masuk dijamin kerahasiaannya.
                    </p>
                </div>
            </div>

        </div>
        <!-- GRID FORM -->
        <form action="{{ route('konsuli.laporan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Identitas Pelapor --}}
                <div class="card">
                    <h3 class="font-semibold mb-3">Identitas Pelapor</h3>

                    
                    <label>Nama Pelapor<span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor') }}" class="w-full p-2 rounded mb-3">
                    @error('nama_pelapor')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <label>Peran Pelapor<span class="text-red-500">*</span></label>
                    <select name="peran_pelapor" class="w-full p-2 rounded mb-3">
                        <option value="">--Pilih--</option>
                        <option value="Mahasiswa" {{ old('peran_pelapor') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="Dosen" {{ old('peran_pelapor') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="Tendik" {{ old('peran_pelapor') == 'Tendik' ? 'selected' : '' }}>Tendik</option>
                    </select>
                    @error('peran_pelapor')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <label>NIP/NPM</label>
                    <input type="text" name="npm_nip" value="{{ old('npm_nip') }}" class="w-full p-2 rounded mb-3">

                    <label>No Telp</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="w-full p-2 rounded mb-3">

                    <label>Email<span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full p-2 rounded mb-3">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mb-3">Pastikan email yang Anda masukkan aktif</p>

                    <label>Jenis Kelamin<span class="text-red-500">*</span></label>
                    <select name="jk_pelapor" value="{{ old('jk_pelapor') }}" class="w-full p-2 rounded mb-3">
                        <option value="">--Pilih--</option>
                        <option value="Laki-laki" {{ old('jk_pelapor') == 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="Perempuan" {{ old('jk_pelapor') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jk_pelapor')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Identitas Terlapor --}}
                <div class="card">
                    <h3 class="font-semibold mb-3">Identitas Terlapor</h3>

                    <label>Nama Terlapor<span class="text-red-500">*</span></label>
                    <input type="text" name="nama_terlapor" value="{{ old('nama_terlapor') }}" class="w-full p-2 rounded mb-3">
                    @error('nama_terlapor')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <label>Peran Terlapor<span class="text-red-500">*</span></label>
                    <select name="peran_terlapor" class="w-full p-2 rounded mb-3">
                        <option value="">--Pilih--</option>
                        <option value="Mahasiswa" {{ old('peran_terlapor') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="Dosen" {{ old('peran_terlapor') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="Tendik" {{ old('peran_terlapor') == 'Tendik' ? 'selected' : '' }}>Tendik</option>
                    </select>
                    @error('peran_terlapor')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <label>No Telp</label>
                    <input type="text" name="no_telp_terlapor" value="{{ old('no_telp_terlapor') }}" class="w-full p-2 rounded mb-3">

                    <label>Jenis Kelamin<span class="text-red-500">*</span></label>
                    <select name="jk_terlapor" class="w-full p-2 rounded mb-3">
                        <option value="">--Pilih--</option>
                        <option value="Laki-laki" {{ old('jk_terlapor') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jk_terlapor') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>

                    </select>
                    @error('jk_terlapor')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <label>Alamat Terlapor</label>
                    <input type="text" name="alamat_terlapor" value="{{ old('alamat_terlapor') }}" class="w-full p-2 rounded mb-3">

                    <label>Jenis Kasus/Indikasi<span class="text-red-500">*</span></label>
                    <select name="jenis_kasus" class="w-full p-2 rounded mb-3">
                        <option value="">--Pilih--</option>
                        <option value="Pengguna" {{ old('jenis_kasus') == 'Pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="Pengedar" {{ old('jenis_kasus') == 'Pengedar' ? 'selected' : '' }}>Pengedar</option>
                        <option value="Kurir" {{ old('jenis_kasus') == 'Kurir' ? 'selected' : '' }}>Kurir</option>
                        <option value="Bandar" {{ old('jenis_kasus') == 'Bandar' ? 'selected' : '' }}>Bandar</option>
                    </select>
                    @error('jenis_kasus')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Kejadian --}}
            <div class="card mt-6">
                <h3 class="font-semibold mb-3">Kejadian</h3>

                <label>Lokasi Kejadian<span class="text-red-500">*</span></label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full p-2 rounded mb-3">
                @error('lokasi')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                <label>Upload Foto Lokasi</label>
                <input type="file" name="foto_lokasi" value="{{ old('foto_lokasi') }}" class="w-full p-2 rounded mb-3">
                @error('foto_lokasi')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                <p class="text-xs text-gray-600 mb-3">Ukuran file maksimal 2MB dengan format .jpg, .jpeg, .png</p>

                <label>Tanggal Kejadian<span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="w-full p-2 rounded mb-3">
                @error('tanggal')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                <label>Jenis Narkoba</label>
                <select id="jenis_narkoba" name="jenis_narkoba_id" class="w-full rounded-lg mb-3">
                    @if(old('jenis_narkoba'))
                        <option value="{{ old('jenis_narkoba') }}" selected>
                            {{ old('jenis_narkoba') }}
                        </option>
                    @endif
                </select>

                @error('jenis_narkoba')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

                <label>Uraian Kronologi<span class="text-red-500">*</span></label>
                <textarea name="kronologi" class="w-full p-2 rounded mb-3">{{ old('kronologi') }}</textarea>
                @error('kronologi')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
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

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    new TomSelect("#jenis_narkoba", {
        valueField: 'id',
        labelField: 'nama',
        searchField: 'nama',
        create: true,
        preload: true,

        load: function(query, callback) {
            fetch(`/jenis-narkoba/search?q=${query}`)
                .then(response => response.json())
                .then(data => callback(data))
                .catch(() => callback());
        },

        placeholder: "Ketik atau pilih jenis narkoba"
    });
});
</script>
@endpush