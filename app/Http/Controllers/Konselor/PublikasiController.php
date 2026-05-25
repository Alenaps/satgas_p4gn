<?php
namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use App\Models\PublikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $publikasi = PublikasiModel::query();

        // SEARCH
        if ($request->filled('search')) {
            $publikasi->where('judul', 'like', '%' . $request->search . '%');
        }

        // FILTER
        if ($request->filled('kategori')) {
            $publikasi->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $publikasi->where('status', $request->status);
        }

        $publikasi = $publikasi->latest()->paginate(10)->appends($request->all());
        return view('konselor.publikasi.index', compact('publikasi'));
    }

    public function create()
    {
        $kategori = ['Artikel','Jurnal','Berita'];
        $status   = ['Draft','Publish'];
        return view('konselor.publikasi.create', compact('kategori','status'));
    }

    public function store(Request $request)
    {
        // VALIDASI
        $this->validatePublikasi($request);

        // UPLOAD
        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('publikasi', 'public');
        }

        PublikasiModel::create([
            'judul'     => $request->judul,
            'slug'      => $request->slug,
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

        return redirect()->route('konselor.publikasi.index')->with('success','Publikasi berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $publikasi = PublikasiModel::findOrFail($id);
        $kategori = ['Artikel','Jurnal','Berita'];
        $status   = ['Draft','Publish'];
        return view('konselor.publikasi.edit', compact('publikasi','kategori','status'));
    }

    public function update(Request $request,int $id)
    {
        $item = PublikasiModel::findOrFail($id);

        // VALIDASI
        $this->validatePublikasi($request, $item->id);

        // THUMBNAIL
        $thumbnail = $item->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($thumbnail && Storage::disk('public')->exists($thumbnail)) {
                Storage::disk('public')->delete($thumbnail);
            }

            $thumbnail = $request->file('thumbnail')->store('publikasi', 'public');
        }

        $item->update([
            'judul'     => $request->judul,
            'slug'      => $request->slug,
            'isi'       => $request->isi,
            'ringkasan' => $request->ringkasan,
            'kutipan'   => $request->kutipan,
            'kategori'  => $request->kategori,
            'status'    => $request->status,
            'label'     => $request->label,
            'keyword'   => $request->keyword,
            'thumbnail' => $thumbnail,
            'user_id'   => auth()->id(),
        ]);

        return redirect()->route('konselor.publikasi.index')
            ->with('success','Publikasi diperbarui.');
    }

    public function destroy(PublikasiModel $publikasi)
    {
        if ($publikasi->thumbnail && Storage::disk('public')->exists($publikasi->thumbnail)) {
            Storage::disk('public')->delete($publikasi->thumbnail);
        }

        $publikasi->delete();

        return back()->with('success','Publikasi dihapus!');
    }

    // VALIDASI
    private function validatePublikasi(Request $request, $id = null)
    {
        return $request->validate([
            'judul'     => 'required|string|min:5|max:191',

            'slug'      => [
                'nullable',
                'string',
                Rule::unique('publikasis','slug')->ignore($id)
            ],

            'isi'       => 'required|string|min:50',
            'ringkasan' => 'required|string|max:300',
            'kutipan'   => 'nullable|string|max:300',
            'keyword'   => 'nullable|string|max:255',
            'label'     => 'nullable|string|max:100',

            'kategori'  => 'required|in:Artikel,Jurnal,Berita',
            'status'    => 'required|in:Draft,Publish',

            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ], [

            'judul.required' => 'Judul wajib diisi.',
            'judul.min'      => 'Judul minimal 5 karakter.',
            'judul.max'      => 'Judul maksimal 191 karakter.',

            'slug.unique'    => 'Slug sudah digunakan.',

            'isi.required'   => 'Isi publikasi wajib diisi.',
            'isi.min'        => 'Isi minimal 50 karakter.',

            'ringkasan.required' => 'Ringkasan wajib diisi.',
            'ringkasan.max'      => 'Ringkasan maksimal 300 karakter.',

            'kutipan.max' => 'Kutipan maksimal 300 karakter.',

            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in'       => 'Kategori tidak valid.',

            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',

            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format harus JPG, JPEG, PNG.',
            'thumbnail.max'   => 'Ukuran maksimal 2MB.',
        ]);
    }
}