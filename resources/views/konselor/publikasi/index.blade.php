@extends('layouts.konselor')

@section('title','Publikasi')

@section('content')

<div class="max-w-7xl mx-auto mt-10">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-800 tracking-wide">Daftar Publikasi</h1>
        
        <a href="{{ route('konselor.publikasi.create') }}"
            class="bg-green-600 hover:bg-green-700 transition text-white px-5 py-2 rounded-lg shadow">
            + Tambah Publikasi
        </a>
    </div>

    <!-- FILTER & SEARCH -->
    <form method="GET" class="bg-white p-4 rounded-lg shadow border border-blue-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- SEARCH -->
            <input type="text" 
                  name="search" 
                  value="{{ request('search') }}"
                  placeholder="Cari judul..."
                  class="border rounded-lg px-4 py-2 w-full">

            <!-- FILTER KATEGORI -->
            <select name="kategori" class="border rounded-lg px-4 py-2 w-full">
                <option value="">-- Semua Kategori --</option>
                <option value="Artikel" {{ request('kategori')=='Artikel'?'selected':'' }}>Artikel</option>
                <option value="Berita" {{ request('kategori')=='Berita'?'selected':'' }}>Berita</option>
                <option value="Jurnal" {{ request('kategori')=='Jurnal'?'selected':'' }}>Jurnal</option>
            </select>

            <!-- FILTER STATUS -->
            <select name="status" class="border rounded-lg px-4 py-2 w-full">
                <option value="">-- Semua Status --</option>
                <option value="Publish" {{ request('status')=='Publish'?'selected':'' }}>Publish</option>
                <option value="Draft" {{ request('status')=='Draft'?'selected':'' }}>Draft</option>
            </select>

            <!-- BUTTON -->
            <div class="flex gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
                    Terapkan
                </button>

                <a href="{{ route('konselor.publikasi.index') }}"
                  class="bg-gray-400 text-white px-4 py-2 rounded-lg text-center w-full">
                    Reset
                </a>
            </div>

        </div>
    </form>


    <!-- TABLE WRAPPER -->
    <div class="overflow-x-auto bg-white rounded-xl shadow border border-blue-200">

        <table class="w-full text-left">
            <thead class="bg-blue-600 border-b border-blue-200 text-white">
                <tr>
                    <th class="p-4 font-semibold">Thumbnail</th>
                    <th class="p-4 font-semibold">Judul</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-800">
                @forelse ($publikasi as $item)
                <tr class="border-b hover:bg-blue-50 transition">

                    <!-- THUMBNAIL -->
                    <td class="p-4">
                        @if($item->thumbnail)
                        <img src="{{ asset('storage/'.$item->thumbnail) }}"
                             class="w-20 h-16 object-cover rounded-md shadow border border-blue-200">
                        @else
                        <span class="text-gray-500 italic">Tidak ada</span>
                        @endif
                    </td>

                    <!-- JUDUL -->
                    <td class="p-4 font-semibold text-blue-900">
                        {{ $item->judul }}
                    </td>

                    <!-- KATEGORI -->
                    <td class="p-4 capitalize text-gray-700">
                        {{ $item->kategori }}
                    </td>

                    <!-- STATUS -->
                    <td class="p-4">
                        @if($item->status === 'Publish')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                Publish
                            </span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                                Draft
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="p-4 flex gap-3 items-center">

                        <a href="{{ route('konselor.publikasi.edit', $item->id) }}"
                           class="text-blue-700 font-semibold hover:text-blue-900">
                            Edit
                        </a>

                        <form action="{{ route('konselor.publikasi.destroy',$item->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus publikasi ini?')"
                                    class="text-red-600 font-semibold hover:text-red-800">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">
                        Tidak ada data publikasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $publikasi->links() }}
    </div>

</div>

@endsection
