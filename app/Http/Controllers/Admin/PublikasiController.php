<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $publikasi = PublikasiModel::query();
        
        //search
        if ($request->search){
            $publikasi->where('judul', 'like', '%'.request()->search.'%');
        }

        //filter
        if ($request->kategori){
            $publikasi->where('kategori', request()->kategori);
        }
        if ($request->status){
            $publikasi->where('status', request()->status);
        }


        $publikasi = $publikasi->latest()->paginate(10)->appends($request->all());
        return view('admin.publikasi.index', compact('publikasi'));
    }

    public function create()
    {
        $kategori = ['Artikel','Jurnal','Berita'];
        $status   = ['Draft','Publish'];
        return view('admin.publikasi.create', compact('kategori','status'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'judul'     => 'required|string|min:5|max:191',
            'slug'      => 'nullable|unique:publikasis,slug',
            'isi'       => 'required|min:50',
            'ringkasan' => 'required|max:300',
            'kategori'  => ['required', Rule::in(['Artikel','Jurnal','Berita'])],
            'status'    => ['required', Rule::in(['Draft','Publish'])],
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ],[
            'judul.required' => 'Judul wajib diisi.',
            'judul.min'      => 'Judul minimal 5 karakter.',
            'judul.max'      => 'Judul maksimal 191 karakter.',

            'slug.unique' => 'Slug sudah digunakan.',

            'isi.required' => 'Isi publikasi wajib diisi.',
            'isi.min'      => 'Isi minimal 50 karakter.',

            'ringkasan.required' => 'Ringkasan wajib diisi.',
            'ringkasan.max' => 'Ringkasan maksimal 300 karakter.',

            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in'       => 'Kategori tidak valid.',

            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',

            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'thumbnail.max'   => 'Ukuran gambar maksimal 4MB.',
        ]);

        // slug otomatis jika tidak diisi
        $slug = $request->slug ?: Str::slug($request->judul);
        $base = $slug; $i = 1;
        while(PublikasiModel::where('slug',$slug)->exists()){
            $slug = $base . '-' . $i++;
        }

        // UPLOAD 
        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('publikasi', 'public');
        }

        PublikasiModel::create([
            'judul'     => $request->judul,
            'slug'      => $slug,
            'isi'       => $request->isi,
            'ringkasan' => $request->ringkasan,
            'kutipan'   => $request->kutipan,
            'keyword'   => $request->keyword,
            'kategori'  => $request->kategori,
            'status'    => $request->status,
            'label'     => $request->label,
            'thumbnail' => $thumbnail,
            'user_id'   => auth()->id(),
        ]);

        return redirect()->route('admin.publikasi.index')->with('success','Publikasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $publikasi = PublikasiModel::findOrFail($id);
        $kategori = ['Artikel','Jurnal','Berita'];
        $status   = ['Draft','Publish'];
        return view('admin.publikasi.edit', compact('publikasi','kategori','status'));
    }

    public function update(Request $request, $id)
    {
        $item = PublikasiModel::findOrFail($id);

        $this->validate($request, [
            'judul'     => 'required|string|min:5|max:191',
            'slug'      => ['nullable', Rule::unique('publikasis','slug')->ignore($item->id)],
            'isi'       => 'required|min:50',
            'ringkasan' => 'required|max:300',
            'kutipan'   => 'nullable|max:300',
            'kategori'  => ['required', Rule::in(['Artikel','Jurnal','Berita'])],
            'keyword'   => 'nullable',
            'label'     => 'nullable',
            'status'    => ['required', Rule::in(['Draft','Publish'])],
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ],[
            'judul.required' => 'Judul wajib diisi.',
            'judul.min'      => 'Judul minimal 5 karakter.',
            'judul.max'      => 'Judul maksimal 191 karakter.',

            'slug.unique' => 'Slug sudah digunakan.',

            'isi.required' => 'Isi publikasi wajib diisi.',
            'isi.min'      => 'Isi minimal 50 karakter.',

            'ringkasan.required' => 'Ringkasan wajib diisi.',
            'ringkasan.max' => 'Ringkasan maksimal 300 karakter.',

            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in'       => 'Kategori tidak valid.',

            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',

            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'thumbnail.max'   => 'Ukuran gambar maksimal 4MB.',
        ]);

        // slug otomatis kalau slug dikosongkan
        $slug = $request->slug ?: Str::slug($request->judul);
        $base = $slug; $i = 1;
        while(PublikasiModel::where('slug',$slug)->where('id','!=',$item->id)->exists()){
            $slug = $base . '-' . $i++;
        }

        // UPLOAD 
        if ($request->hasFile('thumbnail')) {
            // hapus file lama
            if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
                Storage::disk('public')->delete($item->thumbnail);
            }
            // upload baru
            $item->thumbnail = $request->file('thumbnail')->store('publikasi', 'public');
        }

        $item->update([
            'judul'     => $request->judul,
            'slug'      => $slug,
            'isi'       => $request->isi,
            'ringkasan' => $request->ringkasan,
            'kutipan'   => $request->kutipan,
            'catatan'   => $request->catatan,
            'kategori'  => $request->kategori,
            'status'    => $request->status,
            'label'     => $request->label,
            'keyword'   => $request->keyword,
            'thumbnail' => $item->thumbnail,
            'user_id'   => auth()->id(),
        ]);

        return redirect()->route('admin.publikasi.index')->with('success','Publikasi diperbarui.');
    }

    public function destroy(PublikasiModel $publikasi)
    {
        if($publikasi->thumbnail && Storage::disk('public')->exists($publikasi->thumbnail)){
            Storage::disk('public')->delete($publikasi->thumbnail);
        }

        $publikasi->delete();

        return back()->with('success','Publikasi dihapus!');
    }
}
