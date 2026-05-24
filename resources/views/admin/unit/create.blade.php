@extends('layouts.admin')
@section('title', 'Tambah Unit')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.unit.index') }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left text-gray-500 text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Unit</h2>
            <p class="text-sm text-gray-500 mt-0.5">Input manual atau import banyak data sekaligus via Excel</p>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
            <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('import_errors'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg mb-5">
            <p class="font-semibold mb-1"><i class="fas fa-exclamation-circle mr-1"></i>Beberapa baris gagal diimport:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach(session('import_errors') as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- NOTIFIKASI DUPLIKAT --}}
    @if(session('import_duplicates'))
        <div class="bg-yellow-50 border border-yellow-300 rounded-xl p-5 mb-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
                <h3 class="font-semibold text-yellow-800 text-base">
                    Ditemukan {{ count(session('import_duplicates')) }} data duplikat
                </h3>
            </div>
            <p class="text-sm text-yellow-700 mb-4">
                Data berikut sudah ada di database. Pilih tindakan yang ingin dilakukan:
            </p>

            <div class="overflow-hidden rounded-lg border border-yellow-200 mb-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-yellow-100 text-yellow-800">
                            <th class="text-left px-3 py-2 font-semibold">Baris</th>
                            <th class="text-left px-3 py-2 font-semibold">Nama Unit</th>
                            <th class="text-left px-3 py-2 font-semibold">Kategori di Database</th>
                            <th class="text-left px-3 py-2 font-semibold">Kategori di File</th>
                            <th class="text-left px-3 py-2 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-yellow-100">
                        @foreach(session('import_duplicates') as $dup)
                        <tr class="bg-white">
                            <td class="px-3 py-2 text-gray-600">{{ $dup['row'] }}</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $dup['nama'] }}</td>
                            <td class="px-3 py-2">
                                <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-medium">{{ $dup['kat_lama'] }}</span>
                            </td>
                            <td class="px-3 py-2">
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-medium">{{ $dup['kat_baru'] }}</span>
                            </td>
                            <td class="px-3 py-2">
                                <a href="{{ route('admin.unit.edit', $dup['id']) }}"
                                   class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-edit"></i> Edit Manual
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('admin.unit.importConfirm') }}">
                    @csrf
                    <input type="hidden" name="tmp_path" value="{{ session('import_tmp_path') }}">
                    <input type="hidden" name="action" value="overwrite">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt"></i> Timpa Semua Data Lama
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.unit.importConfirm') }}">
                    @csrf
                    <input type="hidden" name="tmp_path" value="{{ session('import_tmp_path') }}">
                    <input type="hidden" name="action" value="skip">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                        <i class="fas fa-forward"></i> Lewati Semua Duplikat
                    </button>
                </form>

                <a href="{{ route('admin.unit.create') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                    <i class="fas fa-redo"></i> Upload Ulang File
                </a>
            </div>

            @if(session('import_imported_so_far') > 0)
                <p class="text-xs text-yellow-700 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ session('import_imported_so_far') }} data non-duplikat sudah berhasil diimport.
                </p>
            @endif
        </div>
    @endif

    {{-- WRAPPER ALPINE --}}
    <div x-data="{ tab: '{{ session('_tab', old('_tab', 'manual')) }}' }">

        {{-- TAB BUTTONS --}}
        <div class="flex gap-1 border-b border-gray-200">
            <button @click="tab = 'manual'"
                :class="tab==='manual' ? 'bg-white border-b-white text-green-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2.5 text-sm border border-b-0 border-gray-200 rounded-t-lg transition-colors -mb-px">
                <i class="fas fa-pen mr-1.5"></i> Input Manual
            </button>
            <button @click="tab = 'import'"
                :class="tab==='import' ? 'bg-white border-b-white text-green-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2.5 text-sm border border-b-0 border-gray-200 rounded-t-lg transition-colors -mb-px">
                <i class="fas fa-file-excel mr-1.5"></i> Import Excel
            </button>
        </div>

        {{-- TAB MANUAL --}}
        <div x-show="tab === 'manual'" x-cloak class="w-full">
            <div class="bg-white rounded-b-xl rounded-tr-xl border border-gray-200 p-6 max-w-lg">
                <form id="form-unit-create" action="{{ route('admin.unit.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_tab" value="manual">
                    @include('components.duplicate-modal')

                    <div class="mb-5">
                        <label for="nama_unit" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Unit <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_unit" name="nama_unit" value="{{ old('nama_unit') }}"
                            placeholder="Contoh: Fakultas Teknik" autocomplete="off"
                            class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition {{ $errors->has('nama_unit') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                        @error('nama_unit')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="kategori_unit" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Kategori Unit <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori_unit" name="kategori_unit"
                            class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white {{ $errors->has('kategori_unit') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Akademik"     {{ old('kategori_unit')==='Akademik'     ? 'selected':'' }}>Akademik</option>
                            <option value="Administrasi" {{ old('kategori_unit')==='Administrasi' ? 'selected':'' }}>Administrasi</option>
                        </select>
                        @error('kategori_unit')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('admin.unit.index') }}"
                            class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB IMPORT --}}
        <div x-show="tab === 'import'" x-cloak class="w-full">
            <div class="bg-white rounded-b-xl rounded-tr-xl border border-gray-200 p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- KOLOM KIRI: Upload --}}
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-upload text-green-600"></i> Upload File Excel
                        </h3>

                        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-lg px-4 py-3 mb-5">
                            <i class="fas fa-file-excel text-green-600 text-xl"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-green-800">Belum punya template?</p>
                                <p class="text-xs text-green-700">Download template kosong dengan panduan lengkap</p>
                            </div>
                            <a href="{{ route('admin.unit.template') }}"
                                class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-2 rounded-lg transition-colors whitespace-nowrap">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>

                        <form action="{{ route('admin.unit.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_tab" value="import">

                            <div class="mb-5">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Pilih File <span class="text-red-500">*</span>
                                </label>

                                {{-- DROPZONE --}}
                                <div id="dropzone"
                                    class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition-colors group relative"
                                    onclick="document.getElementById('file_import').click()">

                                    <div id="dropzone-placeholder" class="flex flex-col items-center gap-2 text-gray-400 group-hover:text-green-600 transition-colors pointer-events-none">
                                        <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                        <p class="text-sm font-medium">Klik atau drag & drop file di sini</p>
                                        <p class="text-xs">.csv — Maks. 2MB</p>
                                    </div>

                                    <div id="dropzone-selected" class="hidden flex-col items-center gap-2 text-green-600 pointer-events-none">
                                        <i class="fas fa-file-excel text-3xl"></i>
                                        <p class="text-sm font-semibold" id="selected-filename">-</p>
                                        <p class="text-xs text-gray-500">Klik untuk ganti file</p>
                                    </div>

                                    {{-- Input file tersembunyi --}}
                                    <input type="file" id="file_import" name="file_import"
                                        accept=".csv,text/csv,application/csv,application/vnd.ms-excel"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>

                                @error('file_import')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                                    <i class="fas fa-file-import"></i> Proses Import
                                </button>
                                <a href="{{ route('admin.unit.index') }}"
                                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- KOLOM KANAN: Panduan --}}
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i> Panduan Import
                        </h3>

                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Struktur Kolom</p>
                            <div class="overflow-hidden rounded-lg border border-gray-200">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-200">
                                            <th class="px-3 py-2 font-semibold text-gray-600 text-left">Kolom</th>
                                            <th class="px-3 py-2 font-semibold text-gray-600 text-left">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <td class="px-3 py-2 font-mono font-semibold text-green-700">nama_unit</td>
                                            <td class="px-3 py-2 text-gray-600">Nama unit/fakultas. <span class="text-red-500 font-medium">Wajib.</span></td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="px-3 py-2 font-mono font-semibold text-green-700">kategori_unit</td>
                                            <td class="px-3 py-2 text-gray-600">
                                                <span class="text-red-500 font-medium">Wajib.</span>
                                                <span class="bg-blue-100 text-blue-700 px-1.5 rounded">Akademik</span> atau
                                                <span class="bg-orange-100 text-orange-700 px-1.5 rounded">Administrasi</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="space-y-1.5 mb-4">
                            <div class="flex items-start gap-2 text-xs bg-green-50 text-green-800 px-3 py-2 rounded-lg">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                <span>Data dimulai dari <strong>baris ke-2</strong> (setelah header)</span>
                            </div>
                            <div class="flex items-start gap-2 text-xs bg-green-50 text-green-800 px-3 py-2 rounded-lg">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                <span>Baris 1 (header) <strong>jangan dihapus</strong></span>
                            </div>
                            <div class="flex items-start gap-2 text-xs bg-yellow-50 text-yellow-800 px-3 py-2 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 flex-shrink-0"></i>
                                <span>Tidak boleh ada <strong>baris kosong</strong> di antara data</span>
                            </div>
                            <div class="flex items-start gap-2 text-xs bg-blue-50 text-blue-800 px-3 py-2 rounded-lg">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                                <span>Jika ada data duplikat, sistem akan meminta <strong>konfirmasi</strong> sebelum menimpa</span>
                            </div>
                            <div class="flex items-start gap-2 text-xs bg-red-50 text-red-800 px-3 py-2 rounded-lg">
                                <i class="fas fa-times-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                                <span>Jangan ubah <strong>nama kolom</strong> header</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg border border-gray-200 px-4 py-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Format & Batas</p>
                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-700">
                                <div><span class="text-gray-400">Format:</span> <strong>.csv</strong></div>
                                <div><span class="text-gray-400">Maks. ukuran:</span> <strong>2 MB</strong></div>
                                <div><span class="text-gray-400">Maks. baris:</span> <strong>500 baris</strong></div>
                                <div><span class="text-gray-400">Sheet:</span> <strong>pertama</strong></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- end x-data --}}
</div>

@push('scripts')
<script>
const DUPLICATE_CHECK_URL = '{{ route('admin.unit.check-duplicate') }}';
const DUPLICATE_FIELD     = 'nama_unit';
const DUPLICATE_INPUT_ID  = 'nama_unit';
const EXCLUDE_ID          = null;
const FORM_ID             = 'form-unit-create';
</script>
@include('components.duplicate-check-script')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input    = document.getElementById('file_import');
    const dropzone = document.getElementById('dropzone');

    function showSelectedFile(file) {
        document.getElementById('dropzone-placeholder').classList.add('hidden');
        const sel = document.getElementById('dropzone-selected');
        sel.classList.remove('hidden');
        sel.classList.add('flex');
        document.getElementById('selected-filename').textContent = file.name;
        dropzone.classList.add('border-green-400', 'bg-green-50');
        dropzone.classList.remove('border-gray-300');
    }

    if (input) {
        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                showSelectedFile(this.files[0]);
            }
        });
    }

    if (dropzone) {
        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('border-green-400', 'bg-green-50');
        });

        dropzone.addEventListener('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!input.files || !input.files[0]) {
                this.classList.remove('border-green-400', 'bg-green-50');
            }
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const files = e.dataTransfer.files;
            if (files && files[0]) {
                // Set file ke input element
                const dt = new DataTransfer();
                dt.items.add(files[0]);
                input.files = dt.files;
                showSelectedFile(files[0]);
            }
        });
    }
});
</script>
@endpush
@endsection