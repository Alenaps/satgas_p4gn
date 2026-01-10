@extends('layouts.admin')

@section('title','Edit Pengguna')

@section('content')

<div class="max-w-xl mx-auto p-6">

    <h2 class="text-3xl font-bold text-blue-800 mb-6">Edit Pengguna</h2>

    <div class="bg-white shadow-lg rounded-xl p-6 border border-blue-200">

        <form action="{{ route('admin.kelola_pengguna.update', $user->id) }}" method="POST" novalidate>
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="font-semibold text-blue-800">Nama</label>
                <input type="text" name="nama" value="{{ $user->nama }}"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50">
                @error('nama')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <div class="mb-4">
                <label class="font-semibold text-blue-800">Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50">
                @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <div class="mb-4">
                <label class="font-semibold text-blue-800">Role</label>
                <select name="role"
                        class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50">
                    <option value="">Pilih Role</option>
                    <option value="konselor" {{ $user->role=='konselor'?'selected':'' }}>Konselor</option>
                    <option value="konsuli" {{ $user->role=='konsuli'?'selected':'' }}>Konsuli</option>
                </select>
                @error('role')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <div class="mb-6">
                <label class="font-semibold text-blue-800">Password Baru (Opsional)</label>
                <input type="password" name="password"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50"
                       placeholder="Isi jika ingin mengganti password">
                @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <div class="mb-6">
                <label class="font-semibold text-blue-800">Konfirmasi Password Baru (Opsional)</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50"
                       placeholder="Isi jika ingin mengganti password">
                @error('password_confirmation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Update
            </button>

        </form>

    </div>

</div>

@endsection
