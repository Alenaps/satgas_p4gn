@extends('layouts.konselor')
@section('title',$publikasi->judul)

@section('content')
<div class="bg-white">
    <div class="bg white max-w-4xl mx-auto py-10">

        <h1 class="text-3xl font-bold">{{ $publikasi->judul }}</h1>

        <p class="text-gray-600 mt-2">
            {{ $publikasi->created_at->format('d M Y') }} • {{ $publikasi->kategori }}
        </p>

        <img src="{{ asset('storage/'.$publikasi->thumbnail) }}"
            class="w-full rounded-xl mt-6">

        <article class="prose mt-8">{!! $publikasi->isi !!}</article>

        <div class="flex justify-between mt-10 text-blue-600">
            @if($prev)
                <a href="{{ route('guest.publikasi.show',$prev->slug) }}">← {{ $prev->judul }}</a>
            @else <span></span> @endif

            @if($next)
                <a href="{{ route('guest.publikasi.show',$next->slug) }}">{{ $next->judul }} →</a>
            @endif
        </div>

        <h3 class="font-bold mt-10">Artikel Terkait</h3>
        <div class="grid md:grid-cols-3 gap-6 mt-4">
            @foreach($related as $r)
                <a href="{{ route('guest.publikasi.show',$r->slug) }}">
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
