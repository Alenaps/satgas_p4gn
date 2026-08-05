@extends('layouts.guest')
@section('title', 'Publikasi Guest')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
  .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
  .font-body { font-family: 'Inter', sans-serif; }

  .p4gn-card {
    transition: transform .25s ease, box-shadow .25s ease;
  }
  .p4gn-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px -10px rgba(11,20,36,0.18);
  }

  @media (prefers-reduced-motion: reduce) {
    .p4gn-card { transition: none; }
  }
</style>
@endpush

@section('content')
<div class="font-body bg-[#F7F8FA]">

    {{-- Hero Background --}}
    <div class="relative w-full h-64 bg-cover bg-center"
         style="background-image: url('/assets/banner.jpeg');">

        <div class="absolute inset-0 bg-gradient-to-b from-[#0B1424]/85 via-[#0B1424]/70 to-[#0B1424]/90"></div>
        <div class="absolute inset-0"
             style="background: radial-gradient(ellipse 70% 60% at 15% 0%, rgba(45,212,191,0.22), transparent 60%),
                                radial-gradient(ellipse 55% 50% at 90% 15%, rgba(245,166,35,0.16), transparent 60%);">
        </div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full px-4">
            <span class="font-display text-xs font-700 text-[#2DD4BF] uppercase tracking-widest mb-2">
                Publikasi Satgas P4GN
            </span>

            <h1 class="font-display text-white text-3xl font-800 mb-5">News & Articles</h1>

            {{-- Search & Filter --}}
            <form method="GET"
                  action="{{ route('guest.publikasi.index') }}"
                  class="flex flex-col sm:flex-row gap-3 justify-center items-center w-11/12 max-w-xs sm:max-w-2xl mx-auto">

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari publikasi..."
                    class="font-body text-slate-800 w-full sm:w-56 px-4 py-2.5 border border-white/20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2DD4BF]">

                <select
                    name="kategori"
                    class="font-body w-full sm:w-48 px-4 py-2.5 border border-white/20 rounded-lg text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2DD4BF]">

                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->kategori }}"
                            {{ request('kategori') == $k->kategori ? 'selected' : '' }}>
                            {{ $k->kategori }}
                        </option>
                    @endforeach

                </select>

                <button
                    class="font-display w-full sm:w-auto px-8 py-2.5 bg-[#0F7A6E] hover:bg-[#0B5E54] text-white font-700 rounded-lg shadow-sm transition-colors">
                    Cari
                </button>

            </form>

        </div>
    </div>


    {{-- CARD LIST --}}
    <section class="max-w-7xl mx-auto mt-12 px-4 pb-20">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">

            @forelse($publikasi as $p)

                <article class="p4gn-card bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">

                    {{-- Thumbnail --}}
                    <div class="relative">
                        <img src="{{ $p->thumbnail ? asset('storage/'.$p->thumbnail) : asset('assets/publikasi-default.jpg') }}"
                            class="w-full h-48 object-cover"
                            alt="{{ $p->judul }}">
                        <span class="font-display absolute top-3 left-3 text-[11px] font-700 text-[#0F7A6E] bg-white/95 px-2.5 py-1 rounded-full uppercase tracking-wide shadow-sm">
                            {{ $p->kategori }}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="p-5">

                        <h3 class="font-display font-700 text-[#0B1424] text-lg leading-snug mb-2 line-clamp-2">
                            {{ $p->judul }}
                        </h3>

                        <p class="text-sm text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $p->ringkasan }}
                        </p>

                        <a href="{{ route('guest.publikasi.show', $p->slug) }}"
                           class="group font-display inline-flex items-center gap-1.5 text-sm font-700 text-[#0F7A6E] hover:text-[#0B1424] mt-4 transition-colors">
                            Read More
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>

                    </div>

                </article>

            @empty

                @if(request()->filled('q') || request()->filled('kategori'))

                    <div class="col-span-full py-20">

                        <div class="max-w-lg mx-auto text-center">

                            <div class="w-16 h-16 mx-auto rounded-full bg-[#E7F8F5] flex items-center justify-center mb-5">
                                <svg class="w-7 h-7 text-[#0F7A6E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                                </svg>
                            </div>

                            <h2 class="font-display text-2xl font-700 text-[#0B1424]">
                                Hasil pencarian tidak ditemukan
                            </h2>

                            <p class="text-slate-500 mt-3 leading-relaxed text-sm">
                                Maaf, kami tidak menemukan publikasi yang sesuai
                                dengan kata kunci atau kategori yang Anda pilih.
                                Silakan coba menggunakan kata kunci lain atau
                                lihat seluruh publikasi yang tersedia.
                            </p>

                            <a href="{{ route('guest.publikasi.index') }}"
                               class="font-display inline-block mt-6 px-6 py-3 bg-[#0B1424] hover:bg-[#14213D] text-white font-700 rounded-xl shadow transition-colors">
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