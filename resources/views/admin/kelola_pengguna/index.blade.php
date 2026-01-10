@extends('layouts.admin')

@section('title','Kelola Pengguna')

@section('content')

<div class="max-w-7xl mx-auto p-6">

    <!-- TITLE -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-blue-800 tracking-wide">
            KELOLA PENGGUNA
        </h2>

        <a href="{{ route('admin.kelola_pengguna.create') }}"
           class="bg-green-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-green-700">
           + Tambah Pengguna
        </a>
    </div>

    <!-- SEARCH & FILTER -->
    <form method="GET"
        class="bg-white p-5 rounded-xl shadow border border-blue-200
                flex flex-col md:flex-row gap-4 mb-6">

        <!-- SEARCH -->
        <div class="flex-1">
            <input type="text" name="search"
                value="{{ request('search') }}"
                class="w-full border border-blue-300 rounded-lg p-3
                        focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Cari nama / email...">
        </div>

        <!-- FILTER ROLE -->
        <div class="md:w-1/4">
            <select name="role"
                    class="w-full border border-blue-300 rounded-lg p-3
                        focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Semua Role</option>
                <option value="konselor" {{ request('role')=='konselor'?'selected':'' }}>
                    Konselor
                </option>
                <option value="konsuli" {{ request('role')=='konsuli'?'selected':'' }}>
                    Konsuli
                </option>
            </select>
        </div>

        <!-- BUTTON -->
        <div class="flex flex-col md:flex-row gap-2 md:w-1/4">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-3
                        rounded-lg w-full transition-colors duration-200">
                Terapkan
            </button>

            <a href="{{ route('konselor.publikasi.index') }}"
            class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold px-4 py-3
                    rounded-lg text-center w-full transition-colors duration-200">
                Reset
            </a>
        </div>
    </form>

    <!-- TABLE -->
    <div class="bg-white shadow-lg rounded-xl border border-blue-200 overflow-hidden">

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="p-4 text-left font-semibold">Nama</th>
                    <th class="p-4 text-left font-semibold">Email</th>
                    <th class="p-4 text-center font-semibold">Role</th>
                    <th class="p-4 text-center font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-800 divide-y divide-blue-100">

                @foreach($users as $user)
                    @if($user->role !== 'admin')
                        <tr class="hover:bg-blue-50 transition">

                    <td class="p-4 font-medium">
                        {{ $user->nama }}
                    </td>

                    <td class="p-4">
                        {{ $user->email }}
                    </td>

                    <!-- BADGE ROLE -->
                    <td class="p-4 text-center">
                        @php
                            $badgeColor = match($user->role){
                                'admin' => 'bg-red-100 text-red-700 border-red-300',
                                'konselor' => 'bg-blue-100 text-blue-700 border-blue-300',
                                'konseli' => 'bg-green-100 text-green-700 border-green-300',
                                default => 'bg-gray-100 text-gray-700 border-gray-300'
                            };
                        @endphp

                        <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeColor }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-3">

                            <a href="{{ route('admin.kelola_pengguna.edit', $user->id) }}"
                                class="bg-blue-600 text-white px-4 py-1 rounded-full text-xs hover:bg-blue-700">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.kelola_pengguna.destroy', $user->id) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf @method('DELETE')

                                <button class="bg-red-500 text-white px-4 py-1 rounded-full text-xs hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                    @endif
                @endforeach

            </tbody>
        </table>

    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $users->withQueryString()->links('pagination::tailwind') }}
    </div>

</div>

@endsection
