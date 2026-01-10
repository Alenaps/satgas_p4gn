@extends('layouts.admin')

@section('title','Tambah Pengguna')

@section('content')

<div class="max-w-xl mx-auto p-6">

    <h2 class="text-3xl font-bold text-blue-800 mb-6">Tambah Pengguna</h2>

    <div class="bg-white shadow-lg rounded-xl p-6 border border-blue-200">
       
        <form action="{{ route('admin.kelola_pengguna.store') }}" method="POST" novalidate>
            @csrf

            <div class="mb-4">
                <label class="font-semibold text-blue-800">Nama</label>
                <input type="text" name="nama"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50">
                    @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <div class="mb-4">
                <label class="font-semibold text-blue-800">Email</label>
                <input type="email" name="email"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50">
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="font-semibold text-blue-800">Role</label>
                <select name="role"
                        class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50" >
                    <option value="">-- pilih --</option>
                    <option value="konselor">Konselor</option>
                    <option value="konsuli">Konsuli</option>
                </select>
                @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="font-semibold text-blue-800">Password</label>
                <input type="password" name="password"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50">
                @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="font-semibold text-blue-800">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-blue-300 rounded-lg p-3 bg-blue-50">
                @error('password_confirmation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Simpan
            </button>

        </form>

    </div>

</div>

@endsection
