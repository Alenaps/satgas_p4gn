@extends('layouts.konselor')

@section('title', 'Detail Sesi Konseling')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-8">
    <div class="max-w-5xl mx-auto px-4">

        <!-- Back Button -->
        <a href="{{ route('konselor.konseling.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 mb-6 transition-all hover:gap-3 group">
            <svg class="w-5 h-5 group-hover:transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Kembali ke Daftar Konseling</span>
        </a>

        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border-2 border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Detail Sesi Konseling</h1>
                <span class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                    Selesai
                </span>
            </div>

            <!-- Info Konseli -->
            <div class="flex items-center gap-4 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-200">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-xl">{{ strtoupper(substr($session->konseli->nama, 0, 1)) }}</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-lg">{{ $session->konseli->nama }}</h3>
                    <p class="text-sm text-gray-600">{{ $session->konseli->npm_nip }}</p>
                </div>
            </div>
        </div>

        <!-- Statistik Sesi -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-6 border-2 border-green-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-green-600">{{ $session->messages->count() }}</p>
                        <p class="text-xs text-gray-600">Total Pesan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 border-2 border-blue-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ $session->started_at->diffInMinutes($session->ended_at) }}
                        </p>
                        <p class="text-xs text-gray-600">Menit</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 border-2 border-purple-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-purple-600">{{ $session->started_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-600">Tanggal</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 border-2 border-orange-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-orange-600">{{ $session->started_at->format('H:i') }}</p>
                        <p class="text-xs text-gray-600">Waktu Mulai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan Konselor (jika ada) -->
        @if($session->catatan_konselor)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border-2 border-yellow-200">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 mb-2">Catatan Konselor</h3>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $session->catatan_konselor }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Riwayat Percakapan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-gray-200">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                Riwayat Percakapan
            </h3>

            <div class="space-y-4 max-h-[600px] overflow-y-auto">
                @forelse($session->messages as $message)
                    @if($message->sender_type === 'konselor')
                        <!-- Pesan Konselor (Kanan) -->
                        <div class="flex justify-end">
                            <div class="max-w-[70%]">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl rounded-tr-md p-4 shadow">
                                    <p class="text-sm whitespace-pre-line">{{ $message->message }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 text-right">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Pesan Konseli (Kiri) -->
                        <div class="flex justify-start">
                            <div class="max-w-[70%]">
                                <div class="bg-gray-100 text-gray-800 rounded-2xl rounded-tl-md p-4 shadow">
                                    <p class="text-sm whitespace-pre-line">{{ $message->message }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p>Tidak ada percakapan</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection