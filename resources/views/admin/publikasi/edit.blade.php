@extends('layouts.admin')
@section('title','Edit Publikasi')
@section('content')

<div class="mt-6 px-4">
  <form id="formPublikasi" 
        action="{{ route('admin.publikasi.update', $publikasi->id) }}" 
        method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="flex flex-col lg:flex-row gap-6">

      {{-- LEFT --}}
      <div class="lg:w-3/4 bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Edit Publikasi</h2>

        <label class="block mb-3">
          <span class="text-sm">Judul</span>
          <input id="judul" name="judul" required minlength="5"
                 value="{{ old('judul', $publikasi->judul) }}"
                 class="w-full border px-3 py-2 rounded"/>
          <small id="judulErr" class="text-red-600 hidden">Minimal 5 karakter</small>
        </label>

        <label class="block mb-3">
          <span class="text-sm">Slug (otomatis)</span>
          <input id="slug" name="slug"
                 value="{{ old('slug', $publikasi->slug) }}"
                 class="w-full border px-3 py-2 rounded" />
          <small class="text-gray-500">Bisa dikosongkan — akan dibuat dari judul.</small>
        </label>

        <label class="block mb-3">
          <span class="text-sm">Ringkasan</span>
          <textarea id="ringkasan" name="ringkasan" maxlength="300"
            class="w-full border px-3 py-2 rounded">{{ old('ringkasan', $publikasi->ringkasan) }}</textarea>
        </label>

        <label class="block mb-3">
          <span class="text-sm">Isi Konten</span>
          <textarea id="isi" name="isi" required minlength="50"
            class="w-full border px-3 py-2 rounded">{{ old('isi', $publikasi->isi) }}</textarea>
          <small id="isiErr" class="text-red-600 hidden">Minimal 50 karakter</small>
        </label>

        <label class="block mb-3">
          <span class="text-sm">Kutipan</span>
          <input name="kutipan" value="{{ old('kutipan', $publikasi->kutipan) }}"
                 class="w-full border px-3 py-2 rounded" />
          <small id="kutipanErr" class="text-red-600 hidden">Maksimal 300 karakter</small>
        </label>

        <label class="block mb-3">
          <span class="text-sm">Keyword</span>
          <input id="keyword" name="keyword" value="{{ old('keyword', $publikasi->keyword) }}"
                 class="w-full border px-3 py-2 rounded" />
          <small id="keywordErr" class="text-red-600 hidden">Wajib diisi</small>
        </label>

        <div class="flex gap-3 mt-4">
          <button type="button" id="previewBtn" class="bg-gray-200 px-4 py-2 rounded">Preview</button>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </div>
      </div>

      {{-- RIGHT --}}
      <div class="lg:w-1/4 space-y-4">

        <div class="bg-yellow-300 p-4 rounded">
          <label>Kategori</label>
          <select id="kategori" name="kategori" class="w-full mt-2 p-2 rounded">
            <option value="">-- pilih --</option>
            @foreach($kategori as $k)
              <option value="{{ $k }}" {{ $k == $publikasi->kategori ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
          </select>
        </div>

        <div class="bg-yellow-300 p-4 rounded">
          <label>Status</label>
          <select id="status" name="status" class="w-full mt-2 p-2 rounded">
            <option value="">-- pilih --</option>
            @foreach($status as $s)
              <option value="{{ $s }}" {{ $s == $publikasi->status ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
          </select>
        </div>

        {{-- THUMBNAIL --}}
        <div class="bg-yellow-300 p-4 rounded">
          <label class="block mb-2">Thumbnail / Cover</label>

          <div id="thumbPreview" class="w-full h-36 bg-gray-100 rounded flex items-center justify-center text-gray-500 overflow-hidden">
            @if($publikasi->thumbnail)
              <img src="/storage/{{ $publikasi->thumbnail }}" class="w-full h-full object-cover rounded">
            @else
              Cover
            @endif
          </div>

          <div class="mt-3 flex items-center gap-2">
            <label for="thumbnail"
                  class="flex-shrink-0 cursor-pointer bg-white border border-gray-400 text-sm px-3 py-2 rounded hover:bg-gray-50 transition">
              Ganti File
            </label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden" />

            <span id="thumbFileName"
                  class="min-w-0 flex-1 text-xs text-gray-700 bg-white border rounded px-2 py-2 truncate"
                  @if($publikasi->thumbnail) title="{{ basename($publikasi->thumbnail) }}" @endif>
              @if($publikasi->thumbnail)
                {{ basename($publikasi->thumbnail) }}
              @else
                Belum ada file dipilih
              @endif
            </span>
          </div>

          <small class="text-gray-600 block mt-2">Ukuran file maksimal 2MB format .jpg, .jpeg, .png</small>
          @error('thumbnail')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="bg-yellow-300 p-4 rounded">
          <label>Label</label>
          <input name="label" value="{{ old('label', $publikasi->label) }}"
                 class="w-full mt-2 p-2 rounded" />
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

  // INIT 
  tinymce.init({
    selector:'#isi',
    height: 550,
    menubar: false,
    plugins: 'lists link image table code paste help',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | removeformat | code',
    paste_as_text: true
  });

  // AUTO SLUG
  const judul = document.getElementById('judul');
  const slug  = document.getElementById('slug');
  judul.addEventListener('input', function(){
    let s = this.value.toLowerCase()
              .replace(/[^a-z0-9\s-]/g,'')
              .trim()
              .replace(/\s+/g,'-');
    slug.value = s;
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
    nameEl.title = file.name;

    const url = URL.createObjectURL(file);
    document.getElementById('thumbPreview').innerHTML =
      `<img src="${url}" class="w-full h-full object-cover rounded">`;
  });

  // PREVIEW MODAL
  document.getElementById('previewBtn').addEventListener('click', function(){
    const title = document.getElementById('judul').value;
    const ring = document.getElementById('ringkasan').value;
    const content = tinymce.get('isi').getContent();
    const kategori = document.getElementById('kategori').value;
    const status = document.getElementById('status').value;

    let html = `<h1 class="text-2xl font-bold mb-2">${title}</h1>`;
    html += `<p class="text-sm text-gray-500 mb-4">${kategori} • ${status}</p>`;
    if(ring) html += `<p class="italic mb-4">${ring}</p>`;
    html += `<div class="prose">${content}</div>`;

    document.getElementById('previewContent').innerHTML = html;
    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
  });

  document.getElementById('closePreview').addEventListener('click', function(){
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewModal').classList.remove('flex');
  });

// ==============================
 // VALIDASI CLIENT-SIDE 
// ==============================

// Helper tampilkan error
    function showErr(id, msg){
        let el = document.getElementById(id);
        el.innerText = msg;
        el.classList.remove("hidden");
    }
    function hideErr(id){
        document.getElementById(id).classList.add("hidden");
    }

    function validateEdit() {
        let valid = true;

        // ===== JUDUL =====
        let judul = document.getElementById("judul").value.trim();
        if (judul.length < 5) {
            showErr("judulErr", "Judul minimal 5 karakter.");
            valid = false;
        } else hideErr("judulErr");

        // ===== SLUG =====
        let slug = document.getElementById("slug").value.trim();
        if (slug === "") {
            // otomatis generate dari judul
            slug = judul.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
            document.getElementById("slug").value = slug;
        }

        // ===== RINGKASAN =====
        let ring = document.getElementById("ringkasan").value;
        if (ring.length > 300) {
            alert("Ringkasan maksimal 300 karakter.");
            valid = false;
        }

        // ===== ISI TINY =====
        let body = tinymce.get("isi").getContent({format:'text'}).trim();
        if (body.length < 50) {
            showErr("isiErr", "Isi konten minimal 50 karakter.");
            valid = false;
        } else hideErr("isiErr");

        // ===== KATEGORI =====
        let kategori = document.getElementById("kategori").value;
        if (kategori === "") {
            alert("Kategori wajib dipilih!");
            valid = false;
        }

        // ===== STATUS =====
        let status = document.getElementById("status").value;
        if (status === "") {
            alert("Status wajib dipilih!");
            valid = false;
        }

        // ===== KUTIPAN =====
        let kutipan = document.getElementById("kutipan").value.trim();
        if (kutipan.length > 300) {
            alert("Kutipan maksimal 300 karakter.");
            valid = false;
        }

        // ===== KEYWORD =====
        let keyword = document.getElementById("keyword").value.trim();
        if (keyword === "") {
            showErr("keywordErr", "Keyword wajib diisi.");
            valid = false;
        } else hideErr("keywordErr");

        // ===== THUMBNAIL ===== (opsional pada edit, tapi jika user upload → wajib dicek)
        const file = document.getElementById("thumbnail").files[0];
        if (file) {
            const allowed = ["image/jpeg", "image/png", "image/jpg"];
            if (!allowed.includes(file.type)) {
                alert("Format thumbnail harus JPG atau PNG.");
                valid = false;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert("Ukuran file thumbnail maksimal 2MB.");
                valid = false;
            }
        }

        return valid;
    }

    // CEGAH SUBMIT JIKA TIDAK VALID
    document.getElementById("formPublikasi").addEventListener("submit", function(e){
        if (!validateEdit()) {
            e.preventDefault();
        }
    });

});
</script>
@endpush
