{{-- resources/views/profile/edit.blade.php --}}

@php
    $user = auth()->user();
    $role = $user->role;
    
    // Tentukan layout berdasarkan role
    switch($role) {
        case 'sivitas':
            $layout = 'layouts.konsuli';
            break;
        case 'konselor':
            $layout = 'layouts.konselor';
            break;
        case 'admin':
            $layout = 'layouts.admin';
            break;
        default:
            $layout = 'layouts.konsuli';
            break;
    }
@endphp

@extends($layout)

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-user-edit mr-2"></i>
                Edit Profile
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Kolom Kiri - Foto Profile -->
                    <div class="lg:col-span-1">
                        <div class="text-center">
                            <div class="mb-4 flex justify-center">
                                <div class="relative inline-block">
                                    {{-- Preview Container --}}
                                    <div id="image-preview-wrapper">
                                        @if($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" 
                                                 alt="Profile Picture" 
                                                 class="w-40 h-40 rounded-full object-cover border-4 border-green-500 shadow-lg"
                                                 id="preview-image">
                                        @else
                                            <div class="w-40 h-40 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-green-500 shadow-lg flex items-center justify-center" id="preview-placeholder">
                                                <i class="fas fa-user text-gray-400 text-5xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Camera Button --}}
                                    <label for="foto" class="absolute bottom-2 right-2 bg-green-600 hover:bg-green-700 text-white rounded-full w-12 h-12 flex items-center justify-center cursor-pointer shadow-lg transition-all duration-200 hover:scale-110 z-10">
                                        <i class="fas fa-camera text-lg"></i>
                                    </label>
                                </div>
                            </div>
                            
                            {{-- Hidden File Input --}}
                            <input type="file" 
                                   id="foto" 
                                   name="foto" 
                                   class="hidden" 
                                   accept="image/jpeg,image/jpg,image/png">
                            
                            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $user->nama }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $user->npm_nip }}</p>
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>

                            @error('foto')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: JPG, JPEG, PNG (Max: 2MB)
                            </p>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Form Data -->
                    <div class="lg:col-span-2">
                        <div class="space-y-3">
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="nama" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama', $user->nama) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('nama') border-red-500 @enderror"
                                       required>
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email (Readonly) -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    Email
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="email" 
                                           id="email" 
                                           value="{{ $user->email }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed text-gray-600"
                                           readonly
                                           disabled>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Email tidak dapat diubah
                                </p>
                            </div>

                            <!-- NPM/NIP (Readonly) -->
                            <div>
                                <label for="npm_nip" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    NPM/NIP
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="text" 
                                           id="npm_nip" 
                                           value="{{ $user->npm_nip }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed text-gray-600"
                                           readonly
                                           disabled>
                                </div>
                            </div>

                            <!-- No Telepon -->
                            <div>
                                <label for="no_telp" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    No. Telepon
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="text" 
                                           id="no_telp" 
                                           name="no_telp" 
                                           value="{{ old('no_telp', $user->no_telp) }}"
                                           placeholder="Contoh: 081234567890"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('no_telp') border-red-500 @enderror">
                                </div>
                                @error('no_telp')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-venus-mars text-gray-400 text-sm"></i>
                                    </div>
                                    <select id="jenis_kelamin" 
                                            name="jenis_kelamin"
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all appearance-none @error('jenis_kelamin') border-red-500 @enderror"
                                            required>
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex gap-3 pt-3">
                                <button type="submit" 
                                        class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.index') }}" 
                                   class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk Preview Image --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto');
    const previewWrapper = document.getElementById('image-preview-wrapper');
    
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validasi ukuran file (2MB)
            if (file.size > 2048000) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                this.value = '';
                return;
            }
            
            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
                this.value = '';
                return;
            }
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                previewWrapper.innerHTML = `
                    <img src="${e.target.result}" 
                         alt="Preview" 
                         class="w-40 h-40 rounded-full object-cover border-4 border-green-500 shadow-lg" 
                         id="preview-image">
                `;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection