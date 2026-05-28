@extends('layouts.konselor')

@section('title', 'Akhiri Sesi Konseling')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-6 font-sans relative">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('konselor.konseling.chat', $session->id) }}" 
           class="inline-flex items-center gap-2 text-gray-500 hover:text-teal-600 mb-6 transition-colors group text-sm sm:text-base font-medium">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Kembali ke Obrolan</span>
        </a>

        <div class="grid lg:grid-cols-3 gap-6 sm:gap-8">
            
            <div class="lg:col-span-1">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
        
        {{-- Foto & Nama --}}
        <div class="text-center mb-6 pb-6 border-b border-gray-100">
            <div class="w-16 h-16 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-3 text-teal-600 shadow-sm border border-teal-100 overflow-hidden">
                @if($session->konseli->foto)
                    <img src="{{ asset('storage/' . $session->konseli->foto) }}" alt="Foto" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user text-2xl"></i>
                @endif
            </div>
            <h3 class="font-bold text-gray-800 text-lg">{{ $session->konseli->nama }}</h3>
            <p class="text-sm text-gray-500">{{ $session->konseli->npm_nip }}</p>
            
            <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                Sesi Aktif
            </div>
        </div>

        {{-- Data Diri Konseli --}}
        <div class="space-y-3 mb-6 pb-6 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Data Diri Konseli</p>

            <div class="flex justify-between items-start gap-2">
                <span class="text-sm text-gray-500 flex items-center gap-2 shrink-0">
                    <i class="fas fa-venus-mars text-gray-400 w-3.5 text-center"></i> Jenis Kelamin
                </span>
                <span class="text-sm font-semibold text-gray-700 text-right">
                    {{ $session->konseli->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                </span>
            </div>

            <div class="flex justify-between items-start gap-2">
                <span class="text-sm text-gray-500 flex items-center gap-2 shrink-0">
                    <i class="fas fa-phone text-gray-400 w-3.5 text-center"></i> No. Telp
                </span>
                <span class="text-sm font-semibold text-gray-700 text-right">
                    {{ $session->konseli->no_telp ?? '-' }}
                </span>
            </div>

            <div class="flex justify-between items-start gap-2">
                <span class="text-sm text-gray-500 flex items-center gap-2 shrink-0">
                    <i class="fas fa-envelope text-gray-400 w-3.5 text-center"></i> Email
                </span>
                <span class="text-sm font-semibold text-gray-700 text-right break-all">
                    {{ $session->konseli->email }}
                </span>
            </div>

            <div class="flex justify-between items-start gap-2">
                <span class="text-sm text-gray-500 flex items-center gap-2 shrink-0">
                    <i class="fas fa-id-badge text-gray-400 w-3.5 text-center"></i> Status
                </span>
                <span class="text-sm font-semibold text-gray-700 text-right">
                    {{ $session->konseli->statusSivitas->nama ?? '-' }}
                </span>
            </div>

            <div class="flex justify-between items-start gap-2">
                <span class="text-sm text-gray-500 flex items-center gap-2 shrink-0">
                    <i class="fas fa-building text-gray-400 w-3.5 text-center"></i> Unit
                </span>
                <span class="text-sm font-semibold text-gray-700 text-right">
                    {{ $session->konseli->unit->nama ?? '-' }}
                </span>
            </div>
        </div>

        {{-- Statistik Sesi --}}
        <div class="space-y-3 mb-6 pb-6 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Statistik Sesi</p>

            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="far fa-clock text-gray-400 w-3.5 text-center"></i> Durasi
                </span>
                <span class="text-sm font-semibold text-gray-700">{{ $session->started_at->diffInMinutes(now()) }} Menit</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="far fa-comments text-gray-400 w-3.5 text-center"></i> Total Pesan
                </span>
                <span class="text-sm font-semibold text-gray-700">{{ $session->messages->count() }} Pesan</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="far fa-calendar-alt text-gray-400 w-3.5 text-center"></i> Dimulai
                </span>
                <span class="text-sm font-semibold text-gray-700">{{ $session->started_at->format('H:i') }} WIB</span>
            </div>
        </div>

        {{-- Informasi --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Informasi</p>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-teal-500 mt-1 text-[10px]"></i>
                    <span>Riwayat tersimpan otomatis</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-teal-500 mt-1 text-[10px]"></i>
                    <span>Dapat diakses di menu Riwayat</span>
                </li>
            </ul>
        </div>

    </div>
</div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 mb-1">Akhiri Sesi Konseling</h1>
                        <p class="text-gray-500 text-sm">Berikan catatan penutup sebelum mengakhiri sesi ini secara permanen.</p>
                    </div>

                    <form id="end-session-form" action="{{ route('konselor.konseling.end', $session->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3">
                            <i class="fas fa-exclamation-circle text-rose-500 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-rose-800">Sesi tidak dapat dilanjutkan</p>
                                <p class="text-sm text-rose-600 mt-0.5">Pastikan semua keluhan konseli telah dibahas sebelum menekan tombol akhiri.</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label for="catatan_konselor" class="block text-sm font-semibold text-gray-700 mb-2">
                                Catatan Konselor <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            
                            <textarea 
                                name="catatan_konselor" 
                                id="catatan_konselor" 
                                rows="6"
                                placeholder="Tulis kesimpulan, progress, atau rekomendasi tindak lanjut dari sesi ini..."
                                class="w-full px-4 py-3 bg-slate-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-100 focus:border-teal-500 transition-all resize-none text-sm text-gray-700 placeholder-gray-400"
                            >{{ old('catatan_konselor') }}</textarea>
                            
                            @error('catatan_konselor')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i> {{ $message }}
                                </p>
                            @enderror

                            <div class="mt-3 flex items-start gap-2 text-gray-500">
                                <i class="fas fa-lock text-xs mt-1 text-gray-400"></i>
                                <p class="text-xs leading-relaxed">
                                    Catatan ini bersifat <strong>rahasia</strong> dan hanya dapat dilihat oleh Anda sebagai referensi sesi mendatang.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('konselor.konseling.chat', $session->id) }}" 
                               class="w-full sm:w-auto px-6 py-3 text-gray-600 hover:bg-gray-100 font-medium rounded-xl transition-colors text-center text-sm">
                                Batal
                            </a>
                            <button 
                                type="button" 
                                id="btn-show-modal"
                                class="w-full sm:w-auto sm:ml-auto px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-medium rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2 text-sm"
                            >
                                <i class="fas fa-power-off"></i>
                                Akhiri Sesi Sekarang
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<div id="custom-modal" class="hidden fixed inset-0 z-[9999] bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform scale-100">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-500">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900 mb-2">Akhiri sesi konseling?</h3>
            <p class="text-sm text-gray-500 mb-6">
                Anda tidak akan bisa membalas atau menerima pesan lagi di sesi ini. Lanjutkan?
            </p>
            
            <div class="flex gap-3">
                <button type="button" id="btn-cancel-modal" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors text-sm">
                    Batal
                </button>
                <button type="button" id="btn-confirm-modal" class="flex-1 px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-medium rounded-xl transition-colors text-sm">
                    Ya, Akhiri
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('custom-modal');
        const btnShowModal = document.getElementById('btn-show-modal');
        const btnCancelModal = document.getElementById('btn-cancel-modal');
        const btnConfirmModal = document.getElementById('btn-confirm-modal');
        const form = document.getElementById('end-session-form');

        // Tampilkan modal saat tombol "Akhiri Sesi Sekarang" diklik
        btnShowModal.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });

        // Sembunyikan modal saat tombol "Batal" di dalam modal diklik
        btnCancelModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Submit form saat tombol "Ya, Akhiri" diklik
        btnConfirmModal.addEventListener('click', function() {
            // Bisa tambahkan state loading disini kalau mau
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            this.disabled = true;
            form.submit();
        });

        // Opsional: Tutup modal kalau klik area luar (backdrop)
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endsection