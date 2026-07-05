@extends('layouts.konselor')

@section('title', 'Data Laporan Kasus')

@section('content')

<div class="max-w-7xl mx-auto mt-10">

    <h1 class="text-2xl font-bold mb-8 text-blue-800 tracking-wide">
        DATA LAPORAN KASUS
    </h1>

    <!-- FILTER & SEARCH -->
    <form method="GET"
        class="bg-white p-4 rounded-lg shadow border border-blue-200 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- SEARCH -->
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama / lokasi..."
                class="border rounded-lg px-4 py-2 w-full">

            <!-- FILTER TANGGAL -->
            <input type="month"
                name="bulan"
                value="{{ request('bulan') }}"
                class="border rounded-lg px-4 py-2 w-full">

            <!-- FILTER PERAN TERLAPOR-->
            <select name="peran_terlapor" class="text-sm border rounded-lg px-4 py-2 w-full">
                <option value="">-- Semua Peran Terlapor --</option>
                <option value="Mahasiswa" {{ request('peran_terlapor')=='Mahasiswa'?'selected':'' }}>
                    Mahasiswa
                </option>
                <option value="Dosen" {{ request('peran_terlapor')=='Dosen'?'selected':'' }}>
                    Dosen
                </option>
                <option value="Tendik" {{ request('peran_terlapor')=='Tendik'?'selected':'' }}>
                    Tendik
                </option>
            </select>

            <!-- BUTTON -->
            <div class="flex gap-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
                    Terapkan
                </button>

                <a href="{{ route('konselor.laporan.index') }}"
                class="bg-gray-400 text-white px-4 py-2 rounded-lg text-center w-full">
                    Reset
                </a>
            </div>

        </div>
    </form>

    <!-- TABLE WRAPPER -->
    <div class="overflow-x-auto bg-white rounded-xl shadow border border-blue-200">

        <table class="w-full text-sm min-w-[800px]">
            <thead>
                <tr class="bg-green-600 text-white border-b border-blue-200">
                    <th class="p-4 text-left font-semibold">NO</th>
                    <th class="p-4 text-left font-semibold">TANGGAL</th>
                    <th class="p-4 text-left font-semibold">NAMA TERLAPOR</th>
                    <th class="p-4 text-left font-semibold">LOKASI KEJADIAN</th>
                    <th class="p-4 text-left font-semibold">FOTO LOKASI</th>
                    <th class="p-4 text-left font-semibold">AKSI</th>
                </tr>
            </thead>

            <tbody class="text-gray-800 divide-y divide-blue-100">
                @forelse ($laporans as $laporan)
                <tr class="hover:bg-blue-50/70 transition">

                    <!-- NOMOR (FIX PAGINATION) -->
                    <td class="p-4 font-medium">
                        {{ ($laporans->currentPage() - 1) * $laporans->perPage() + $loop->iteration }}
                    </td>

                    <td class="p-4">
                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}
                    </td>

                    <td class="p-4">
                        {{ $laporan->nama_terlapor }}
                    </td>

                    <td class="p-4">
                        {{ $laporan->lokasi }}
                    </td>

                    <td class="p-4">
                        @if ($laporan->foto_lokasi)
                        <img src="{{ asset('storage/' . $laporan->foto_lokasi) }}"
                             class="w-20 h-20 object-cover rounded-lg shadow border border-blue-200">
                        @else
                        <span class="italic text-gray-500">Tidak ada</span>
                        @endif
                    </td>

                    <td class="p-4">
                        <div class="flex gap-2">

                            <a href="{{ route('konselor.laporan.show', $laporan->id) }}"
                               class="px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700 shadow">
                                DETAIL
                            </a>

                            <a href="{{ route('konselor.laporan.cetak.pdf', $laporan->id) }}"
                               class="px-4 py-1.5 rounded-full text-xs font-semibold bg-yellow-500 text-white hover:bg-yellow-600 shadow">
                                PDF
                            </a>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500 italic">
                        Belum ada laporan.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
    
    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $laporans->links() }}
    </div>

</div>

@endsection
