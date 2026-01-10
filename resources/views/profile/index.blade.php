{{-- resources/views/profile/index.blade.php --}}

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

@section('title', 'Profile Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                Profile Saya
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri - Foto Profile -->
                <div class="lg:col-span-1">
                    <div class="text-center">
                        <div class="mb-4 flex justify-center">
                            @if($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}" 
                                     alt="Profile Picture" 
                                     class="w-40 h-40 rounded-full object-cover border-4 border-green-500 shadow-lg">
                            @else
                                <div class="w-40 h-40 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-green-500 shadow-lg flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-5xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $user->nama }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $user->npm_nip }}</p>
                        
                        <div class="flex justify-center gap-2 flex-wrap">
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>
                            
                            @if($user->status_sivitas)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-semibold">
                                    {{ $user->status_sivitas }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan - Data Profile -->
                <div class="lg:col-span-2">
                    <div class="space-y-3">
                        <!-- Nama Lengkap -->
                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                            <p class="text-gray-800 font-medium">{{ $user->nama }}</p>
                        </div>

                        <!-- Email -->
                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-2 text-sm"></i>
                                {{ $user->email }}
                            </p>
                        </div>

                        <!-- NPM/NIP -->
                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">NPM/NIP</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-id-card text-gray-400 mr-2 text-sm"></i>
                                {{ $user->npm_nip }}
                            </p>
                        </div>

                        <!-- No Telepon -->
                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No. Telepon</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-2 text-sm"></i>
                                {{ $user->no_telp ?? '-' }}
                            </p>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jenis Kelamin</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-venus-mars text-gray-400 mr-2 text-sm"></i>
                                {{ $user->jenis_kelamin ?? '-' }}
                            </p>
                        </div>

                        <!-- Status Sivitas -->
                        @if($user->status_sivitas)
                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Sivitas</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-user-graduate text-gray-400 mr-2 text-sm"></i>
                                {{ $user->status_sivitas }}
                            </p>
                        </div>
                        @endif

                        <!-- Tombol Edit -->
                        <div class="pt-2">
                            <a href="{{ route('profile.edit') }}" 
                               class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-edit"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection