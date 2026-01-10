@extends('layouts.konsuli')

@section('title', 'Daftar Konselor')

@section('content')
<div class="min-h-screen bg-gray-50 pb-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Daftar Konselor</h1>
            <p class="text-gray-600">Pilih konselor yang ingin Anda ajukan untuk sesi konseling</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Daftar Konselor -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($konselors as $konselor)
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="flex flex-col items-center text-center">
                    <!-- Foto Konselor -->
                    <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center overflow-hidden mb-4 shadow-lg ring-4 ring-green-100">
                        @if($konselor->foto)
                            <img src="{{ asset('storage/' . $konselor->foto) }}" class="w-full h-full object-cover" alt="{{ $konselor->nama }}">
                        @else
                            <i class="fas fa-user-tie text-white text-3xl"></i>
                        @endif
                    </div>
                    
                    <!-- Info Konselor -->
                    <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $konselor->nama }}</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-id-card mr-1"></i>{{ $konselor->npm_nip }}
                    </p>

                    <!-- Status & Tombol -->
                    @php
                        $hasActiveSession = $sessions->where('konselor_id', $konselor->id)
                            ->whereIn('status', ['pending', 'active'])
                            ->first();
                    @endphp

                    <div class="w-full mt-4">
                        @if($hasActiveSession)
                            @if($hasActiveSession->status == 'pending')
                                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-3 flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 mr-2"></i>
                                    <span class="text-sm text-yellow-800 font-medium">Menunggu Persetujuan</span>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <div class="bg-green-50 border-2 border-green-200 rounded-lg p-3 flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        <span class="text-sm text-green-800 font-medium">Sesi Aktif</span>
                                    </div>
                                    <a href="{{ route('konsuli.konseling.chat', $hasActiveSession->id) }}" 
                                       class="block w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-lg transition-colors font-medium">
                                        <i class="fas fa-comments mr-2"></i>Buka Chat
                                    </a>
                                </div>
                            @endif
                        @else
                            <form action="{{ route('konsuli.konseling.request') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="konselor_id" value="{{ $konselor->id }}">
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-lg transition-colors font-medium shadow-md hover:shadow-lg">
                                    <i class="fas fa-paper-plane mr-2"></i>Ajukan Konseling
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-16 text-center">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-user-tie text-gray-300 text-7xl mb-6"></i>
                        <h3 class="text-2xl font-bold text-gray-700 mb-3">Belum Ada Konselor Tersedia</h3>
                        <p class="text-gray-500 mb-6">Saat ini belum ada konselor yang terdaftar dalam sistem. Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 inline-block">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Info:</strong> Konselor akan segera ditambahkan oleh admin.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Info Box -->
        @if($konselors->count() > 0)
        <div class="mt-10 bg-gradient-to-r from-blue-50 to-green-50 border-l-4 border-blue-400 p-6 rounded-lg shadow-sm">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 text-2xl mr-4 mt-1 flex-shrink-0"></i>
                <div>
                    <h3 class="font-bold text-blue-900 mb-3 text-lg">Cara Mengajukan Konseling:</h3>
                    <ol class="text-sm text-blue-800 space-y-2">
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold mt-0.5">1</span>
                            <span>Pilih konselor yang sesuai dengan kebutuhan Anda</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold mt-0.5">2</span>
                            <span>Klik tombol "Ajukan Konseling"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold mt-0.5">3</span>
                            <span>Tunggu persetujuan dari konselor</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold mt-0.5">4</span>
                            <span>Jika disetujui, Anda dapat memulai sesi chat konseling</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold mt-0.5">5</span>
                            <span>Lihat status pengajuan di menu "KONSELING"</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection