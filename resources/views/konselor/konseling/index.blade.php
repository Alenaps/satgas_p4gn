@extends('layouts.konselor')

@section('title', 'Konseling')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Permintaan Konseling Masuk -->
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-3">Permintaan Konseling Masuk</h2>
            
            @if($pendingSessions->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-3">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-bell mr-2"></i>
                    Anda memiliki {{ $pendingSessions->count() }} permintaan konseling yang menunggu persetujuan.
                </p>
            </div>

            <div class="space-y-3">
                @foreach($pendingSessions as $session)
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($session->konseli->foto)
                            <img src="{{ asset('storage/' . $session->konseli->foto) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user text-gray-400 text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 truncate">{{ $session->konseli->nama }}</h3>
                        <p class="text-xs text-gray-600">{{ $session->konseli->npm_nip }}</p>
                        <span class="inline-block px-2 py-0.5 text-xs rounded-full mt-1 bg-yellow-100 text-yellow-800">
                            Menunggu Persetujuan
                        </span>
                    </div>
                </div>

                <div class="text-xs text-gray-600 mb-3 space-y-1">
                    <p><i class="fas fa-calendar mr-2"></i>{{ $session->created_at->format('d M Y, H:i') }}</p>
                    <p><i class="fas fa-clock mr-2"></i>{{ $session->created_at->diffForHumans() }}</p>
                </div>

                <div class="flex gap-2">
                    <form action="{{ route('konselor.konseling.approve', $session->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition-colors text-sm">
                            <i class="fas fa-check mr-1"></i>Terima
                        </button>
                    </form>
                    <form action="{{ route('konselor.konseling.reject', $session->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition-colors text-sm" 
                                onclick="return confirm('Yakin ingin menolak permintaan ini?')">
                            <i class="fas fa-times mr-1"></i>Tolak
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            </div>
            @else
            <div class="bg-gray-100 p-6 rounded-lg text-center border-2 border-dashed border-gray-300">
                <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                <p class="text-gray-600 text-sm">Tidak ada permintaan konseling</p>
            </div>
            @endif
        </div>

        <!-- Sesi Konseling Aktif -->
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-3">Sesi Konseling Aktif</h2>
            
            @if($activeSessions->count() > 0)
            <div class="bg-green-50 border-l-4 border-green-400 p-3 mb-3">
                <p class="text-sm text-green-700">
                    <i class="fas fa-comments mr-2"></i>
                    Anda memiliki {{ $activeSessions->count() }} sesi konseling aktif.
                </p>
            </div>

            <div class="space-y-3">
                @foreach($activeSessions as $session)
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($session->konseli->foto)
                            <img src="{{ asset('storage/' . $session->konseli->foto) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user text-gray-400 text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 truncate">{{ $session->konseli->nama }}</h3>
                        <p class="text-xs text-gray-600">{{ $session->konseli->npm_nip }}</p>
                        <span class="inline-block px-2 py-0.5 text-xs rounded-full mt-1 bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </div>
                </div>

                <div class="text-xs text-gray-600 mb-3 space-y-1">
                    <p><i class="fas fa-calendar mr-2"></i>{{ $session->started_at->format('d M Y, H:i') }}</p>
                    <p><i class="fas fa-clock mr-2"></i>{{ $session->started_at->diffForHumans() }}</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('konselor.konseling.chat', $session->id) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg transition-colors text-sm">
                        <i class="fas fa-comments mr-1"></i>Buka Chat
                    </a>
                    <a href="{{ route('konselor.konseling.end-form', $session->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition-colors text-sm">
                        <i class="fas fa-check-circle mr-1"></i>Akhiri
                    </a>
                </div>
            </div>
            @endforeach
            </div>
            @else
            <div class="bg-gray-100 p-6 rounded-lg text-center border-2 border-dashed border-gray-300">
                <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                <p class="text-gray-600 text-sm">Tidak ada sesi konseling aktif</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Pesan jika tidak ada sesi pending dan aktif -->
    @if($pendingSessions->count() == 0 && $activeSessions->count() == 0)
    <div class="bg-gradient-to-br from-gray-100 to-gray-200 p-8 rounded-lg text-center mb-6 border-2 border-dashed border-gray-300">
        <i class="fas fa-inbox text-gray-400 text-4xl mb-3"></i>
        <p class="text-gray-600 font-medium">Tidak ada permintaan atau sesi konseling aktif</p>
        <p class="text-gray-500 text-sm mt-2">Permintaan konseling baru akan muncul di sini</p>
    </div>
    @endif

    <!-- Riwayat Konseling -->
    @if($completedSessions->total() > 0)
    <div class="mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-3">Riwayat Konseling</h2>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-600 to-green-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Konseli</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal Selesai</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Durasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($completedSessions as $index => $session)
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-green-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden mr-3 flex-shrink-0">
                                        @if($session->konseli->foto)
                                            <img src="{{ asset('storage/' . $session->konseli->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-gray-400 text-sm"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $session->konseli->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $session->konseli->npm_nip }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $session->started_at?->format('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ $session->ended_at?->format('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                @if($session->started_at && $session->ended_at)
                                    {{ $session->started_at->diffForHumans($session->ended_at, true) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Selesai
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <a href="{{ route('konselor.konseling.detail', $session->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $completedSessions->links() }}
            </div>
        </div>
    </div>
    @endif

</div>
@endsection