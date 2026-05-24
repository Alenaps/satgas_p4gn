@extends('layouts.admin')
@section('title', 'Edit Unit')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.unit.index') }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left text-gray-500 text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Unit</h2>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data: <span class="font-medium text-gray-700">{{ $unit->nama_unit }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-lg">
        <form id="form-unit-edit" action="{{ route('admin.unit.update', $unit) }}" method="POST">
            @csrf
            @method('PUT')
            @include('components.duplicate-modal')

            <div class="mb-5">
                <label for="nama_unit" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Unit <span class="text-red-500">*</span></label>
                <input type="text" id="nama_unit" name="nama_unit" value="{{ old('nama_unit', $unit->nama_unit) }}" placeholder="Contoh: Fakultas Teknik" autocomplete="off"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition {{ $errors->has('nama_unit') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                @error('nama_unit')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="kategori_unit" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori Unit <span class="text-red-500">*</span></label>
                <select id="kategori_unit" name="kategori_unit" class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white {{ $errors->has('kategori_unit') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                    <option value="" disabled>-- Pilih Kategori --</option>
                    <option value="Akademik"     {{ old('kategori_unit', $unit->kategori_unit)==='Akademik'     ? 'selected':'' }}>Akademik</option>
                    <option value="Administrasi" {{ old('kategori_unit', $unit->kategori_unit)==='Administrasi' ? 'selected':'' }}>Administrasi</option>
                </select>
                @error('kategori_unit')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    <i class="fas fa-save"></i> Perbarui
                </button>
                <a href="{{ route('admin.unit.index') }}" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const DUPLICATE_CHECK_URL = '{{ route('admin.unit.check-duplicate') }}';
const DUPLICATE_FIELD     = 'nama_unit';
const DUPLICATE_INPUT_ID  = 'nama_unit';
const EXCLUDE_ID          = {{ $unit->id }};
const FORM_ID             = 'form-unit-edit';
</script>
@include('components.duplicate-check-script')
@endpush
@endsection