@extends('layouts.guest')
@section('title', 'Publikasi Guest')

@section('content')
<div class="bg-white">

    {{-- Hero Background --}}
    <div class="relative w-full h-64 bg-cover bg-center"
         style="background-image: url('/assets/banner.jpeg');">

        <div class="absolute inset-0 bg-black bg-opacity-40"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full">
            <h1 class="text-white text-3xl font-bold mb-4">News & Articles</h1>

            {{-- Search --}}
            <form method="GET"
                  action="{{ route('guest.publikasi.index') }}"
                  class="flex flex-col md:flex-row gap-3 justify-center items-center w-full max-w-xl mx-auto">

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari..."
                    class="text-black w-full md:w-48 px-3 py-2 border rounded-lg">

                <select
                    name="kategori"
                    class="w-full md:w-48 px-3 py-2 border rounded-lg text-gray-600">

                    <option value="">Semua Kategori</option>

                    @foreach($kategori as $k)
                        <option value="{{ $k->kategori }}"
                            {{ request('kategori') == $k->kategori ? 'selected' : '' }}>
                            {{ $k->kategori }}
                        </option>
                    @endforeach

                </select>

                <button
                    class="flex-none w-full md:w-24 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Cari
                </button>

            </form>

        </div>
    </div>


    {{-- CARD LIST --}}
    <section class="max-w-7xl mx-auto mt-12 px-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($publikasi as $p)

                <article class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition">

                    {{-- Thumbnail --}}
                    <img src="{{ $p->thumbnail ? asset('storage/'.$p->thumbnail) : asset('assets/publikasi-default.jpg') }}"
                        class="w-full h-48 object-cover"
                        alt="{{ $p->judul }}">

                    {{-- Content --}}
                    <div class="p-5">

                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">
                            {{ $p->kategori }}
                        </span>

                        <h3 class="font-bold mt-2 text-lg leading-snug">
                            {{ $p->judul }}
                        </h3>

                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                            {{ $p->ringkasan }}
                        </p>

                        <a href="{{ route('guest.publikasi.show', $p->slug) }}"
                           class="text-blue-600 mt-4 inline-block font-medium hover:underline">
                            Read More →
                        </a>

                    </div>

                </article>

            @empty

                @if(request()->filled('q') || request()->filled('kategori'))

                    <div class="col-span-full py-20">

                        <div class="max-w-lg mx-auto text-center">

                            {{-- Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-20 h-20 mx-auto text-gray-300 mb-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>

                            </svg>

                            <h2 class="text-2xl font-bold text-gray-700">
                                Hasil pencarian tidak ditemukan
                            </h2>

                            <p class="text-gray-500 mt-3 leading-relaxed">
                                Maaf, kami tidak menemukan publikasi yang sesuai
                                dengan kata kunci atau kategori yang Anda pilih.
                                Silakan coba menggunakan kata kunci lain atau
                                lihat seluruh publikasi yang tersedia.
                            </p>

                            <a href="{{ route('guest.publikasi.index') }}"
                               class="inline-block mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                                Lihat Semua Publikasi
                            </a>

                        </div>

                    </div>

                @endif

            @endforelse

        </div>

        {{-- Pagination hanya tampil jika ada data --}}
        @if($publikasi->count())
            <div class="mt-12 flex justify-center">
                {{ $publikasi->withQueryString()->links() }}
            </div>
        @endif

    </section>

</div>
@endsection