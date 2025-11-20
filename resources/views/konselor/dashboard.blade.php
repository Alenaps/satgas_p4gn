@extends('layouts.konselor')

@section('title', 'Dashboard Konselor')

@section('content')
<h1 class="text-2xl font-bold mb-4">Selamat datang, {{ auth()->user()->nama }}</h1>

<p>Ini adalah halaman dashboard konselor. Gunakan sidebar untuk navigasi fitur.</p>
@endsection
