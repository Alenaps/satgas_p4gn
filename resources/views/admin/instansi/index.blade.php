@extends('layouts.admin')

@section('title', 'Master Data Instansi')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Master Data Instansi</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola data instansi konselor</p>
        </div>
        <a href="{{ route('admin.instansi.create') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
            <i class="fas fa-plus"></i> Tambah Instansi
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
            <i class="fas fa-check-circle text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('admin.instansi.index') }}" class="flex gap-3 mb-5">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama instansi..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
        </div>
        <button type="submit"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.instansi.index') }}"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                <i class="fas fa-times"></i> Reset
            </a>
        @endif
    </form>

    {{-- Info hasil pencarian --}}
    @if(request('search'))
        <p class="text-sm text-gray-500 mb-3">
            Menampilkan <span class="font-semibold text-gray-700">{{ $instansis->total() }}</span> hasil
            untuk "<span class="font-semibold text-gray-700">{{ request('search') }}</span>"
        </p>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                    <th class="px-6 py-3.5 font-semibold w-14 text-center rounded-tl-xl">No</th>
                    <th class="px-6 py-3.5 font-semibold">Nama Instansi</th>
                    <th class="px-6 py-3.5 font-semibold text-center w-36 rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instansis as $instansi)
                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-green-50 transition-colors border-b border-gray-100 last:border-0">
                        <td class="px-6 py-4 text-center text-gray-400 font-medium">
                            {{ $loop->iteration + ($instansis->currentPage() - 1) * $instansis->perPage() }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $instansi->nama_instansi }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.instansi.edit', $instansi) }}"
                                   class="inline-flex items-center gap-1 text-xs bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-300 px-3 py-1.5 rounded-lg transition-colors font-medium">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                <form action="{{ route('admin.instansi.destroy', $instansi) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus instansi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-100 text-red-600 border border-red-300 px-3 py-1.5 rounded-lg transition-colors font-medium">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <i class="fas fa-search text-4xl"></i>
                                <p class="text-sm font-medium">
                                    @if(request('search'))
                                        Tidak ada instansi yang cocok dengan pencarian.
                                    @else
                                        Belum ada data instansi.
                                    @endif
                                </p>
                                @if(request('search'))
                                    <a href="{{ route('admin.instansi.index') }}" class="text-xs text-green-600 hover:underline mt-1">Tampilkan semua instansi</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($instansis->total() > 0)
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $instansis->firstItem() }}–{{ $instansis->lastItem() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $instansis->total() }}</span> instansi
                </p>
                @if($instansis->hasPages())
                    {{ $instansis->appends(request()->query())->links() }}
                @endif
            </div>
        @endif
    </div>

</div>

@endsection