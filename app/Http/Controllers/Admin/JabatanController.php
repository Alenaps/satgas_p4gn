<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::query();
        if ($request->filled('search')) $query->where('nama_jabatan', 'like', '%'.$request->search.'%');
        return view('admin.jabatan.index', ['jabatans' => $query->latest()->paginate(10)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_jabatan' => 'required|string|max:255']);

        if ($request->filled('force_overwrite') && $request->filled('overwrite_id')) {
            Jabatan::findOrFail($request->overwrite_id)->update($request->only('nama_jabatan'));
            return redirect()->route('admin.jabatan.index')->with('success', 'Data jabatan berhasil ditimpa.');
        }

        Jabatan::create($request->only('nama_jabatan'));
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('admin.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate(['nama_jabatan' => 'required|string|max:255']);

        if ($request->filled('force_overwrite') && $request->filled('overwrite_id')
            && $request->overwrite_id != $jabatan->id) {
            Jabatan::findOrFail($request->overwrite_id)->update($request->only('nama_jabatan'));
            $jabatan->delete();
            return redirect()->route('admin.jabatan.index')->with('success', 'Data jabatan berhasil ditimpa.');
        }

        $jabatan->update($request->only('nama_jabatan'));
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }

    public function checkDuplicate(Request $request)
    {
        $query = Jabatan::whereRaw('LOWER(nama_jabatan) = ?', [strtolower($request->nama_jabatan)]);
        if ($request->filled('exclude_id')) $query->where('id', '!=', $request->exclude_id);
        $existing = $query->first();

        return response()->json($existing
            ? ['exists' => true, 'id' => $existing->id, 'name' => $existing->nama_jabatan, 'detail' => '']
            : ['exists' => false]);
    }
}