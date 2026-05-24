@extends('layouts.guest')
@section('title', 'Publikasi Guest')

@section('content')
<div class="bg-white">

    {{-- Hero Background --}}
    <div class="relative w-full h-64 bg-cover bg-center"
         style="background-image: url('/assets/banner.jpeg');">

        <div class="absolute inset-0 bg-black bg-opacity-40"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-white">
            <h1 class="text-3xl font-bold mb-4">News & Articles</h1>

            {{-- Search --}}
            <form method="GET" action="{{ route('guest.publikasi.index') }}" class="flex gap-3 justify-center">
    
                <input type="text" name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari..."
                    class="px-3 py-2 border rounded-lg text-black">

                <select name="kategori" class="px-3 py-2 border rounded-lg text-gray-600">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->kategori }}" {{ request('kategori')==$k->kategori? 'selected':'' }}>
                            {{ $k->kategori }}
                        </option>
                    @endforeach
                </select>

                <button class="bg-blue-600 px-4 text-white rounded-lg">
                    Cari
                </button>

            </form>

        </div>
    </div>


    {{-- CARD LIST --}}
    <section class="max-w-7xl mx-auto mt-12 px-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($publikasi as $p)
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

                        <a href="{{ route('guest.publikasi.show',$p->slug) }}"
                           class="text-blue-600 mt-4 inline-block font-medium">
                            Read More →
                        </a>
                    </div>
                </article>
            @endforeach

        </div>

        <div class="mt-12 flex justify-center">
            {{ $publikasi->links() }}
        </div>

    </section>

</div>
@endsection
