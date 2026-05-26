@extends('layouts.konsuli')
@section('title',$publikasi->judul)

@section('content')
<div class="bg-white">
    <div class="bg white max-w-4xl mx-auto py-10 px-4">

        <h1 class="text-3xl font-bold">{{ $publikasi->judul }}</h1>

        <p class="text-gray-600 mt-2">
            {{ $publikasi->created_at->format('d M Y') }} • {{ $publikasi->kategori }} • {{ $publikasi->label }} 
        </p>

        <img src="{{ asset('storage/'.$publikasi->thumbnail) }}"
            class="w-full rounded-xl mt-6">

        <article class="prose mt-8">{!! $publikasi->isi !!}</article>

         @if($publikasi->keyword)
            <div class="mt-8">
                <h4 class="font-semibold mb-2">Kata Kunci:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $publikasi->keyword) as $key)
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                            #{{ trim($key) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($publikasi->kutipan)
            <div class="mt-10">
                <h3 class="font-bold text-lg mb-3">Daftar Referensi</h3>
                <ul class="list-decimal pl-5 text-sm text-gray-700 space-y-2">
                    @foreach(explode("\n", $publikasi->kutipan) as $ref)
                        @if(trim($ref) != '')
                            <li>{{ $ref }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="flex justify-between mt-10 text-blue-600">
            @if($prev)
                <a href="{{ route('konsuli.publikasi.show',$prev->slug) }}">← {{ $prev->judul }}</a>
            @else <span></span> @endif

            @if($next)
                <a href="{{ route('konsuli.publikasi.show',$next->slug) }}">{{ $next->judul }} →</a>
            @endif
        </div>

        <h3 class="font-bold mt-10">Artikel Terkait</h3>
        <div class="grid md:grid-cols-3 gap-6 mt-4">
            @foreach($related as $r)
                <a href="{{ route('konsuli.publikasi.show',$r->slug) }}">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/'.$r->thumbnail) }}">
                        <div class="p-3 font-medium">{{ $r->judul }}</div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</div>
@endsection
