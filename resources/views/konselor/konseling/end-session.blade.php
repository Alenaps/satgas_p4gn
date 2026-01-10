@extends('layouts.konselor')

@section('title', 'Akhiri Sesi Konseling')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 py-8">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Back Button -->
        <a href="{{ route('konselor.konseling.chat', $session->id) }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-emerald-600 mb-6 transition-all hover:gap-3 group">
            <svg class="w-5 h-5 group-hover:transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Kembali ke Chat</span>
        </a>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Info & Stats -->
            <div class="lg:col-span-1 space-y-4">
                
                <!-- Info Konseli Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-green-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Informasi Konseli</p>
                            <h3 class="font-bold text-gray-800">{{ $session->konseli->nama }}</h3>
                        </div>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            <span>{{ $session->konseli->npm_nip }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $session->started_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                Sesi Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistik Sesi -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-blue-100">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800">Statistik Sesi</h3>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 text-center border border-green-200">
                            <p class="text-3xl font-bold text-green-600 mb-1">{{ $session->messages->count() }}</p>
                            <p class="text-xs text-gray-600 font-medium">Total Pesan</p>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-4 text-center border border-blue-200">
                            <p class="text-3xl font-bold text-blue-600 mb-1">{{ $session->started_at->diffInMinutes(now()) }}</p>
                            <p class="text-xs text-gray-600 font-medium">Menit</p>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-5 border-2 border-cyan-200">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-cyan-900 text-sm mb-2">Yang Perlu Diketahui</p>
                            <ul class="space-y-2 text-xs text-cyan-800">
                                <li class="flex items-start gap-2">
                                    <svg class="w-3 h-3 text-cyan-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Riwayat tetap tersimpan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-3 h-3 text-cyan-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Konsuli dapat melihat detail sesi</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-3 h-3 text-cyan-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Masuk ke menu "Riwayat Konseling"</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-gray-100">
                    
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Akhiri Sesi Konseling</h1>
                                <p class="text-sm text-gray-600">Pastikan semua telah selesai sebelum mengakhiri</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('konselor.konseling.end', $session->id) }}" method="POST">
                        @csrf
                        
                        <!-- Warning Box -->
                        <div class="mb-6 p-5 bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-400 rounded-xl">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-amber-900 mb-1">⚠️ Perhatian Penting!</p>
                                    <p class="text-sm text-amber-800 leading-relaxed">
                                        Setelah sesi diakhiri, Anda tidak dapat melanjutkan percakapan. Pastikan semua hal penting telah dibahas dengan Konsuli.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Konselor -->
                        <div class="mb-6">
                            <label for="catatan_konselor" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Catatan Akhir Sesi
                                <span class="ml-auto text-xs text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            
                            <textarea 
                                name="catatan_konselor" 
                                id="catatan_konselor" 
                                rows="8"
                                placeholder="📝 Tulis catatan, kesimpulan, atau rekomendasi dari sesi konseling ini...&#10;&#10;Contoh:&#10;• Konsuli menunjukkan progress positif dalam...&#10;• Perlu follow-up untuk topik...&#10;• Rekomendasi: Saran untuk Konsuli..."
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none placeholder-gray-400"
                            >{{ old('catatan_konselor') }}</textarea>
                            
                            @error('catatan_konselor')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            <div class="mt-3 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-xs text-blue-800">
                                        <strong class="font-semibold">Catatan ini bersifat rahasia</strong> dan hanya dapat dilihat oleh Anda. Gunakan untuk dokumentasi internal atau referensi sesi mendatang.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-stretch gap-3 pt-4">
                            <a href="{{ route('konselor.konseling.chat', $session->id) }}" 
                               class="flex-1 px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all text-center border-2 border-gray-300 hover:border-gray-400 flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Batal
                            </a>
                            <button 
                                type="submit" 
                                onclick="return confirm('⚠️ Yakin ingin mengakhiri sesi konseling ini?\n\nSetelah diakhiri, sesi tidak dapat dilanjutkan kembali.')"
                                class="flex-1 px-6 py-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Akhiri Sesi Sekarang
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection