@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Selamat datang, {{ auth()->user()->nama }}</h1>

<p>Ini adalah halaman dashboard admin. Gunakan sidebar untuk navigasi fitur.</p>
@endsection
