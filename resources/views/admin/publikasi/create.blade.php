@extends('layouts.admin')
@section('title','Tambah Publikasi')
@section('content')

<div class="mt-6 px-4">
  <form id="formPublikasi" action="{{ route('admin.publikasi.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="flex flex-col lg:flex-row gap-6">

      {{-- LEFT --}}
      <div class="lg:w-3/4 bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Tambah Publikasi</h2>

        <label class="block mb-3">
          <span class="text-sm">Judul<span class="text-red-500">*</span></span>
          <input id="judul" name="judul" value="{{ old('judul') }}"
                 class="w-full border px-3 py-2 rounded"/>
        @error('judul')
          <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
        </label>

        <label class="block mb-3">
          <span class="text-sm">Slug (otomatis)</span>
          <input id="slug" name="slug" value="{{ old('slug') }}" class="w-full border px-3 py-2 rounded" />
          @error('slug')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </label>

        <label class="block mb-3">
          <span class="text-sm">Ringkasan<span class="text-red-500">*</span></span>
          <textarea id="ringkasan" name="ringkasan" maxlength="300" class="w-full border px-3 py-2 rounded">{{ old('ringkasan') }}</textarea>
          @error('ringkasan')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </label>

        <label class="block mb-3">
          <span class="text-sm">Isi Konten<span class="text-red-500">*</span></span>
          <textarea id="isi" name="isi" class="w-full border px-3 py-2 rounded">{{ old('isi') }}</textarea>
          @error('isi')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </label>

        <label class="block mb-3">
          <span class="text-sm">Kutipan</span>
          <input name="kutipan" value="{{ old('kutipan') }}" class="w-full border px-3 py-2 rounded" />
          @error('kutipan')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </label>

        <label class="block mb-3">
          <span class="text-sm">Keyword</span>
          <input id="keyword" name="keyword" value="{{ old('keyword') }}" class="w-full border px-3 py-2 rounded" />
          @error('keyword')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </label>

        <div class="flex gap-3 mt-4">
          <button type="button" id="previewBtn" class="bg-gray-200 px-4 py-2 rounded">Preview</button>
          <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </div>
      </div>

      {{-- RIGHT --}}
      <div class="lg:w-1/4 space-y-4">

        <div class="bg-yellow-300 p-4 rounded">
          <label>Kategori<span class="text-red-500">*</span></label>
          <select id="kategori" name="kategori" class="w-full mt-2 p-2 rounded">
            <option value="">-- pilih --</option>
            @foreach($kategori as $k) <option value="{{ $k }}">{{ $k }}</option> @endforeach
          </select>
          @error('kategori')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="bg-yellow-300 p-4 rounded">
          <label>Status<span class="text-red-500">*</span></label>
          <select id="status" name="status" class="w-full mt-2 p-2 rounded">
            <option value="">-- pilih --</option>
            @foreach($status as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
          </select>
          @error('status')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- THUMBNAIL --}}
        <div class="bg-yellow-300 p-4 rounded">
          <label class="block mb-2">Thumbnail / Cover</label>

          <div id="thumbPreview" class="w-full h-36 bg-gray-100 rounded flex items-center justify-center text-gray-500 overflow-hidden">
            Cover
          </div>

          <div class="mt-3 flex items-center gap-2">
            <label for="thumbnail"
                  class="flex-shrink-0 cursor-pointer bg-white border border-gray-400 text-sm px-3 py-2 rounded hover:bg-gray-50 transition">
              Pilih File
            </label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden" />

            <span id="thumbFileName"
                  class="min-w-0 flex-1 text-xs text-gray-700 bg-white border rounded px-2 py-2 truncate">
              Belum ada file dipilih
            </span>
          </div>

          <small class="text-gray-600 block mt-2">Ukuran file maksimal 2MB format .jpg, .jpeg, .png</small>
          @error('thumbnail')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="bg-yellow-300 p-4 rounded">
          <label>Label</label>
          <input name="label" value="{{ old('label') }}" class="w-full mt-2 p-2 rounded" />
        </div>

      </div>
    </div>
  </form>
</div>

{{-- Preview Modal --}}
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center">
  <div class="bg-white w-11/12 md:w-3/4 lg:w-2/3 p-6 rounded shadow-lg overflow-auto max-h-[80vh]">
    <button id="closePreview" class="float-right text-gray-600">Tutup</button>
    <div id="previewContent" class="mt-6"></div>
  </div>
</div>

@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/tinymce.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function(){

  // TinyMCE
  tinymce.init({
    selector:'#isi',
    height: 550,
    plugins: 'lists link image table code paste',
    toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
    paste_as_text: true
  });

  // Auto slug
  document.getElementById('judul').addEventListener('input', function(){
    let s = this.value.toLowerCase()
            .replace(/[^a-z0-9\s-]/g,'')
            .trim()
            .replace(/\s+/g,'-');
    document.getElementById('slug').value = s;
  });

  // THUMBNAIL PREVIEW 
  document.getElementById('thumbnail').addEventListener('change', function(){
    const file = this.files[0];
    const nameEl = document.getElementById('thumbFileName');

    if(!file){
      nameEl.textContent = 'Belum ada file dipilih';
      nameEl.title = '';
      return;
    }

    nameEl.textContent = file.name;
    nameEl.title = file.name; // biar nama lengkap muncul saat di-hover

    const url = URL.createObjectURL(file);
    document.getElementById('thumbPreview').innerHTML =
      `<img src="${url}" class="w-full h-full object-cover rounded">`;
  });

  // PREVIEW MODAL
  document.getElementById('previewBtn').addEventListener('click', function(){

    const title = document.getElementById('judul').value;
    const ringk = document.getElementById('ringkasan').value;
    const kategori = document.getElementById('kategori').value;
    const status = document.getElementById('status').value;

    const content = tinymce.get('isi').getContent();

    // Thumbnail local preview
    let thumbHtml = "";
    const thumbInput = document.getElementById('thumbnail').files[0];
    if(thumbInput){
      const temp = URL.createObjectURL(thumbInput);
      thumbHtml = `<img src="${temp}" class="w-full h-60 object-cover rounded mb-4">`;
    }

    let html = `
      <h1 class="text-2xl font-bold mb-2">${title}</h1>
      <p class="text-sm text-gray-500 mb-4">${kategori} • ${status}</p>
      ${thumbHtml}
      ${ringk ? `<p class="italic mb-4">${ringk}</p>` : ''}
      <div class="prose">${content}</div>
    `;

    document.getElementById('previewContent').innerHTML = html;
    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
  });

  document.getElementById('closePreview').addEventListener('click', function(){
    document.getElementById('previewModal').classList.add('hidden');
  });

});

// ==============================
// VALIDASI CLIENT-SIDE
// ==============================
function showError(inputID, message) {
    let el = document.getElementById(inputID);

    // hapus error lama
    let next = el.nextElementSibling;
    if (next && next.classList.contains("error-msg")) {
        next.remove();
    }

    if (message) {
        let div = document.createElement("div");
        div.className = "error-msg text-red-600 text-sm mt-1";
        div.innerText = message;
        el.after(div);
    }
}

function validateForm() {
    let isValid = true;

    // === JUDUL ===
    let judul = document.getElementById("judul").value.trim();
    if (judul.length < 5) {
        showError("judul", "Judul minimal 5 karakter.");
        isValid = false;
    } else {
        showError("judul", "");
    }

    // === SLUG ===
    let slug = document.getElementById("slug").value.trim();
    if (!slug) {
        showError("slug", "Slug tidak boleh kosong.");
        isValid = false;
    } else {
        showError("slug", "");
    }

    // === RINGKASAN ===
    let ringkasan = document.getElementById("ringkasan").value.trim();
    if (ringkasan.length > 300) {
        showError("ringkasan", "Ringkasan maksimal 300 karakter.");
        isValid = false;
    } else {
        showError("ringkasan", "");
    }

    // === ISI (TINY) ===
    let isiContent = tinymce.get('isi').getContent({ format: 'text' }).trim();
    if (isiContent.length < 50) {
        showError("isi", "Isi konten minimal 50 karakter.");
        isValid = false;
    } else {
        showError("isi", "");
    }

    // === KATEGORI ===
    let kategori = document.getElementById("kategori").value.trim();
    if (!kategori) {
        showError("kategori", "Kategori wajib dipilih.");
        isValid = false;
    } else {
        showError("kategori", "");
    }

    // === STATUS ===
    let status = document.getElementById("status").value.trim();
    if (!status) {
        showError("status", "Status wajib dipilih.");
        isValid = false;
    } else {
        showError("status", "");
    }

    // === THUMBNAIL ===
    let fileInput = document.getElementById('thumbnail');
    let file = fileInput.files[0];

    if (!file) {
        showError("thumbnail", "Thumbnail wajib diunggah.");
        isValid = false;
    } else {
        let allowed = ["image/jpeg", "image/jpg", "image/png"];
        if (!allowed.includes(file.type)) {
            showError("thumbnail", "Format thumbnail harus JPG/PNG.");
            isValid = false;
        } else if (file.size > 2 * 1024 * 1024) {
            showError("thumbnail", "Ukuran maksimal 2MB.");
            isValid = false;
        } else {
            showError("thumbnail", "");
        }
    }

    return isValid;
}

// CEGAH SUBMIT JIKA ADA ERROR
document.getElementById("formPublikasi").addEventListener("submit", function(e) {

    if (!validateForm()) {
        e.preventDefault();
        alert("Masih ada input yang salah. Periksa kembali.");
    }
});


// COUNTER RINGKASAN
document.getElementById("ringkasan").addEventListener("input", function () {
    let max = 300;
    if (this.value.length > max) {
        this.value = this.value.substr(0, max);
    }
});
</script>


@endpush
