@extends('layouts.admin')
@section('title', 'Edit Jabatan')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.jabatan.index') }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left text-gray-500 text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Jabatan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data: <span class="font-medium text-gray-700">{{ $jabatan->nama_jabatan }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-lg">
        <form id="form-jabatan-edit" action="{{ route('admin.jabatan.update', $jabatan) }}" method="POST">
            @csrf
            @method('PUT')
            @include('components.duplicate-modal')

            <div class="mb-6">
                <label for="nama_jabatan" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Jabatan <span class="text-red-500">*</span></label>
                <input type="text" id="nama_jabatan" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" placeholder="Contoh: Konselor Utama" autocomplete="off"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition {{ $errors->has('nama_jabatan') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                @error('nama_jabatan')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    <i class="fas fa-save"></i> Perbarui
                </button>
                <a href="{{ route('admin.jabatan.index') }}" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const DUPLICATE_CHECK_URL = '{{ route('admin.jabatan.check-duplicate') }}';
const DUPLICATE_FIELD     = 'nama_jabatan';
const DUPLICATE_INPUT_ID  = 'nama_jabatan';
const EXCLUDE_ID          = {{ $jabatan->id }};
const FORM_ID             = 'form-jabatan-edit';
</script>
@include('components.duplicate-check-script')
@endpush
@endsection