@extends('layouts.konsuli')

@section('title', 'Publikasi Konsuli')
    {{-- Hero Background --}}
    <div class="relative w-full h-64 bg-cover bg-center"
        style="background-image: url('/images/banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-white">
            <h1 class="text-3xl font-bold mb-4">News & Articles</h1>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('publikasi.index') }}" class="w-full max-w-lg">
                <div class="flex bg-white rounded-full overflow-hidden shadow">
                    <input type="text" name="q" placeholder="Search By"
                           class="w-full px-4 py-2 text-gray-700 focus:outline-none">
                    <button type="submit" class="bg-blue-600 text-white px-4 flex items-center">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- publikasi Cards --}}
    <div class="py-10 max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        {{-- Looping dari database --}}
        @foreach ($publikasis as $publikasi)
        <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-xl transition">
            <img src="{{ asset('storage/publikasi/' . $publikasi->thumbnail) }}"
                 class="h-48 w-full object-cover" alt="thumbnail">

            <div class="p-4">
                <span class="text-sm font-semibold text-blue-600">{{ $publikasi->kategori }}</span>

                <h3 class="mt-2 font-bold text-lg line-clamp-2">{{ $publikasi->judul }}</h3>

                <p class="mt-1 text-sm text-gray-600 line-clamp-3">
                    {{ $publikasi->excerpt }}
                </p>

                <a href="{{ route('publikasi.show', $publikasi->slug) }}"
                   class="text-blue-600 text-sm font-semibold inline-block mt-3">
                    Read More >>
                </a>
            </div>
        </div>
        @endforeach

    </div>
@endsection