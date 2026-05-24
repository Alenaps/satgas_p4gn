{{-- resources/views/profile/edit.blade.php --}}

@php
    $user = auth()->user();
    $role = $user->role;
    
    switch($role) {
        case 'konselor': $layout = 'layouts.konselor'; break;
        case 'admin':    $layout = 'layouts.admin'; break;
        default:         $layout = 'layouts.konsuli'; break;
    }
@endphp

@extends($layout)

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-user-edit mr-2"></i> Edit Profile
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Kolom Kiri - Foto --}}
                    <div class="lg:col-span-1">
                        <div class="text-center">
                            <div class="mb-4 flex justify-center">
                                <div class="relative inline-block">
                                    <div id="image-preview-wrapper">
                                        @if($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Picture"
                                                 class="w-40 h-40 rounded-full object-cover border-4 border-green-500 shadow-lg" id="preview-image">
                                        @else
                                            <div class="w-40 h-40 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-green-500 shadow-lg flex items-center justify-center" id="preview-placeholder">
                                                <i class="fas fa-user text-gray-400 text-5xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <label for="foto" class="absolute bottom-2 right-2 bg-green-600 hover:bg-green-700 text-white rounded-full w-12 h-12 flex items-center justify-center cursor-pointer shadow-lg transition-all duration-200 hover:scale-110 z-10">
                                        <i class="fas fa-camera text-lg"></i>
                                    </label>
                                </div>
                            </div>
                            <input type="file" id="foto" name="foto" class="hidden" accept="image/jpeg,image/jpg,image/png">
                            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $user->nama }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $user->npm_nip }}</p>
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">{{ ucfirst($user->role) }}</span>
                            @error('foto')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle mr-1"></i>Format: JPG, JPEG, PNG (Max: 2MB)</p>
                        </div>
                    </div>

                    {{-- Kolom Kanan - Form --}}
                    <div class="lg:col-span-2">
                        <div class="space-y-3">

                            {{-- Nama --}}
                            <div>
                                <label for="nama" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('nama') border-red-500 @enderror">
                                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email (readonly) --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="email" value="{{ $user->email }}" readonly disabled
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed text-gray-600">
                                </div>
                                <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Email tidak dapat diubah</p>
                            </div>

                            {{-- NPM/NIP (readonly) --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">NPM/NIP</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="text" value="{{ $user->npm_nip }}" readonly disabled
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed text-gray-600">
                                </div>
                            </div>

                            {{-- No. Telepon --}}
                            <div>
                                <label for="no_telp" class="block text-xs font-semibold text-gray-600 uppercase mb-1">No. Telepon</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}"
                                           placeholder="Contoh: 081234567890"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('no_telp') border-red-500 @enderror">
                                </div>
                                @error('no_telp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div>
                                <label for="jenis_kelamin" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-venus-mars text-gray-400 text-sm"></i>
                                    </div>
                                    <select id="jenis_kelamin" name="jenis_kelamin" required
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all appearance-none @error('jenis_kelamin') border-red-500 @enderror">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                                @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- ===== KHUSUS KONSULI ===== --}}
                            @if($user->role === 'konsuli')
                                <div class="border-t pt-4 mt-4">
                                    <h3 class="text-sm font-bold text-gray-600 uppercase mb-3">
                                        <i class="fas fa-user-graduate mr-1"></i> Data Sivitas
                                    </h3>

                                    {{-- Status Sivitas --}}
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status Sivitas</label>
                                        <select name="status_sivitas_id"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('status_sivitas_id') border-red-500 @enderror">
                                            <option value="">-- Pilih Status --</option>
                                            @foreach($statusSivitasList as $status)
                                                <option value="{{ $status->id }}" {{ old('status_sivitas_id', $user->status_sivitas_id) == $status->id ? 'selected' : '' }}>
                                                    {{ $status->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status_sivitas_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Kategori Unit --}}
                                    <div class="mb-3">
                                        <label for="kategori_unit" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kategori Unit</label>
                                        <select id="kategori_unit" name="kategori_unit"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Akademik"     {{ old('kategori_unit', $user->unit?->kategori_unit) == 'Akademik'     ? 'selected' : '' }}>Akademik</option>
                                            <option value="Administrasi" {{ old('kategori_unit', $user->unit?->kategori_unit) == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                                        </select>
                                    </div>

                                    {{-- Unit / Fakultas (difilter by kategori) --}}
                                    <div class="mb-3">
                                        <label for="unit_id" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Unit / Fakultas</label>
                                        <select id="unit_id" name="unit_id"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('unit_id') border-red-500 @enderror">
                                            <option value="">-- Pilih Unit --</option>
                                            @foreach($unitList as $unit)
                                                <option value="{{ $unit->id }}"
                                                        data-kategori="{{ $unit->kategori_unit }}"
                                                        {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->nama_unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- ===== KHUSUS KONSELOR ===== --}}
                            @if($user->role === 'konselor')
                                <div class="border-t pt-4 mt-4">
                                    <h3 class="text-sm font-bold text-gray-600 uppercase mb-3">
                                        <i class="fas fa-user-md mr-1"></i> Data Konselor
                                    </h3>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Nomor Lisensi</label>
                                        <input type="text" name="nomor_lisensi" value="{{ old('nomor_lisensi', $user->konselorProfile->nomor_lisensi ?? '') }}"
                                               class="mt-1 block w-full rounded-md px-3 py-2 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Spesialisasi</label>
                                        <input type="text" name="spesialisasi" value="{{ old('spesialisasi', $user->konselorProfile->spesialisasi ?? '') }}"
                                               class="mt-1 block w-full rounded-md px-3 py-2 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Pengalaman Kerja (Tahun)</label>
                                        <input type="number" name="pengalaman_kerja" min="0" max="50"
                                               value="{{ old('pengalaman_kerja', $user->konselorProfile->pengalaman_kerja ?? '') }}"
                                               class="mt-1 block w-full rounded-md px-3 py-2 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Pendidikan Terakhir</label>
                                        <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $user->konselorProfile->pendidikan_terakhir ?? '') }}"
                                               class="mt-1 block w-full rounded-md px-3 py-2 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Instansi</label>
                                        <select name="id_instansi"
                                                class="mt-1 block w-full rounded-md px-3 py-2 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                                            <option value="">-- Pilih Instansi --</option>
                                            @foreach($instansiList as $instansi)
                                                <option value="{{ $instansi->id }}" {{ old('id_instansi', $user->konselorProfile->id_instansi ?? '') == $instansi->id ? 'selected' : '' }}>
                                                    {{ $instansi->nama_instansi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Jabatan</label>
                                        <select name="id_jabatan"
                                                class="mt-1 block w-full rounded-md px-3 py-2 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach($jabatanList as $jabatan)
                                                <option value="{{ $jabatan->id }}" {{ old('id_jabatan', $user->konselorProfile->id_jabatan ?? '') == $jabatan->id ? 'selected' : '' }}>
                                                    {{ $jabatan->nama_jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 flex items-center gap-3">
                                        <input type="checkbox" name="sertifikasi_P4GN" id="sertifikasi_P4GN" value="1"
                                               {{ old('sertifikasi_P4GN', $user->konselorProfile->sertifikasi_P4GN ?? false) ? 'checked' : '' }}
                                               class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                                        <label for="sertifikasi_P4GN" class="text-sm font-semibold text-gray-700 cursor-pointer">Memiliki Sertifikasi P4GN</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-semibold text-gray-700">Bio Singkat</label>
                                        <textarea name="bio_singkat" rows="4"
                                                  class="mt-1 block w-full rounded-md px-3 py-2 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">{{ old('bio_singkat', $user->konselorProfile->bio_singkat ?? '') }}</textarea>
                                    </div>
                                </div>
                            @endif

                            {{-- Tombol --}}
                            <div class="flex gap-3 pt-3">
                                <button type="submit"
                                        class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.index') }}"
                                   class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Preview foto ──────────────────────────────────────────
    const fotoInput      = document.getElementById('foto');
    const previewWrapper = document.getElementById('image-preview-wrapper');

    if (fotoInput) {
        fotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 2048000) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                this.value = '';
                return;
            }
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewWrapper.innerHTML = `<img src="${e.target.result}" alt="Preview"
                    class="w-40 h-40 rounded-full object-cover border-4 border-green-500 shadow-lg" id="preview-image">`;
            };
            reader.readAsDataURL(file);
        });
    }

    // ── Filter unit berdasarkan kategori (sama seperti register) ──
    const kategoriSelect = document.getElementById('kategori_unit');
    const unitSelect     = document.getElementById('unit_id');

    if (kategoriSelect && unitSelect) {
        // Simpan semua option unit sebelum difilter
        const allUnitOptions = Array.from(unitSelect.options);

        function filterUnit(kategori, selectedUnitId) {
            unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';
            allUnitOptions.forEach(opt => {
                if (opt.value === '') return;
                if (!kategori || opt.dataset.kategori === kategori) {
                    const cloned = opt.cloneNode(true);
                    if (selectedUnitId && cloned.value == selectedUnitId) {
                        cloned.selected = true;
                    }
                    unitSelect.appendChild(cloned);
                }
            });
        }

        kategoriSelect.addEventListener('change', function () {
            filterUnit(this.value, null);
        });

        // Saat load: filter berdasarkan kategori yang sudah tersimpan
        const initialKategori  = kategoriSelect.value;
        const initialUnitId    = "{{ old('unit_id', $user->unit_id ?? '') }}";

        if (initialKategori) {
            filterUnit(initialKategori, initialUnitId);
        }
    }
});
</script>
@endsection