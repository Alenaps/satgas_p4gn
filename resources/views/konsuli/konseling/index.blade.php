@extends('layouts.konsuli')

@section('title', 'Sesi Konseling Saya')

@section('content')
<div class="min-h-screen bg-gray-50 pb-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <!-- Header - CENTERED -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Sesi Konseling Saya</h1>
            <p class="text-gray-600">Kelola dan pantau sesi konseling Anda</p>
        </div>

        <!-- Sesi Pending -->
        @if($sessions->where('status', 'pending')->count() > 0)
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-clock text-yellow-500 mr-2"></i>Menunggu Persetujuan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sessions->where('status', 'pending') as $session)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($session->konselor->foto)
                                <img src="{{ asset('storage/' . $session->konselor->foto) }}" class="w-full h-full object-cover" alt="{{ $session->konselor->nama }}">
                            @else
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $session->konselor->nama }}</h3>
                            <p class="text-sm text-gray-600 truncate">{{ $session->konselor->npm_nip }}</p>
                            <span class="inline-block px-2 py-1 text-xs rounded-full mt-1 bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-calendar mr-2"></i>{{ $session->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="bg-yellow-50 p-3 rounded text-center">
                        <i class="fas fa-hourglass-half text-yellow-600"></i>
                        <p class="text-sm text-yellow-700 mt-1">Menunggu persetujuan konselor</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Sesi Aktif -->
        @if($sessions->where('status', 'active')->count() > 0)
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-comments text-green-500 mr-2"></i>Sesi Aktif
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sessions->where('status', 'active') as $session)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($session->konselor->foto)
                                <img src="{{ asset('storage/' . $session->konselor->foto) }}" class="w-full h-full object-cover" alt="{{ $session->konselor->nama }}">
                            @else
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $session->konselor->nama }}</h3>
                            <p class="text-sm text-gray-600 truncate">{{ $session->konselor->npm_nip }}</p>
                            <span class="inline-block px-2 py-1 text-xs rounded-full mt-1 bg-green-100 text-green-800">
                                <i class="fas fa-circle text-xs mr-1"></i>Aktif
                            </span>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-calendar mr-2"></i>{{ $session->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
                        @if($session->started_at)
                        <p><i class="fas fa-play mr-2"></i>Dimulai: {{ $session->started_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
                        @endif
                    </div>

                    <a href="{{ route('konsuli.konseling.chat', $session->id) }}" 
                       class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2.5 rounded-lg transition-colors font-medium">
                        <i class="fas fa-comments mr-2"></i>Buka Chat
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Riwayat Konseling -->
        @if($sessions->where('status', 'completed')->count() > 0)
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-history text-gray-500 mr-2"></i>Riwayat Konseling
            </h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konselor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sessions->where('status', 'completed') as $session)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden mr-3 flex-shrink-0">
                                            @if($session->konselor->foto)
                                                <img src="{{ asset('storage/' . $session->konselor->foto) }}" class="w-full h-full object-cover" alt="{{ $session->konselor->nama }}">
                                            @else
                                                <i class="fas fa-user text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $session->konselor->nama }}</div>
                                            <div class="text-sm text-gray-500">{{ $session->konselor->npm_nip }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $session->started_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($session->started_at && $session->ended_at)
                                        {{ $session->started_at->diffForHumans($session->ended_at, true) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Selesai
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($sessions->count() == 0)
        <div class="bg-white rounded-lg shadow-md p-16 text-center">
            <div class="max-w-md mx-auto">
                <i class="fas fa-inbox text-gray-300 text-7xl mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-3">Belum Ada Sesi Konseling</h3>
                <p class="text-gray-500 mb-8">Anda belum memiliki sesi konseling. Mulai dengan memilih konselor yang sesuai dengan kebutuhan Anda.</p>
                <a href="{{ route('konsuli.konselor.index') }}" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition-colors font-medium shadow-md hover:shadow-lg">
                    <i class="fas fa-user-tie mr-2"></i>Lihat Daftar Konselor
                </a>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection