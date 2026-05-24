<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index(Request $request)
    {
        $query = Instansi::query();
        if ($request->filled('search')) $query->where('nama_instansi', 'like', '%'.$request->search.'%');
        return view('admin.instansi.index', ['instansis' => $query->latest()->paginate(10)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.instansi.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_instansi' => 'required|string|max:255']);

        if ($request->filled('force_overwrite') && $request->filled('overwrite_id')) {
            Instansi::findOrFail($request->overwrite_id)->update($request->only('nama_instansi'));
            return redirect()->route('admin.instansi.index')->with('success', 'Data instansi berhasil ditimpa.');
        }

        Instansi::create($request->only('nama_instansi'));
        return redirect()->route('admin.instansi.index')->with('success', 'Instansi berhasil ditambahkan.');
    }

    public function edit(Instansi $instansi)
    {
        return view('admin.instansi.edit', compact('instansi'));
    }

    public function update(Request $request, Instansi $instansi)
    {
        $request->validate(['nama_instansi' => 'required|string|max:255']);

        if ($request->filled('force_overwrite') && $request->filled('overwrite_id')
            && $request->overwrite_id != $instansi->id) {
            Instansi::findOrFail($request->overwrite_id)->update($request->only('nama_instansi'));
            $instansi->delete();
            return redirect()->route('admin.instansi.index')->with('success', 'Data instansi berhasil ditimpa.');
        }

        $instansi->update($request->only('nama_instansi'));
        return redirect()->route('admin.instansi.index')->with('success', 'Instansi berhasil diperbarui.');
    }

    public function destroy(Instansi $instansi)
    {
        $instansi->delete();
        return redirect()->route('admin.instansi.index')->with('success', 'Instansi berhasil dihapus.');
    }

    public function checkDuplicate(Request $request)
    {
        $query = Instansi::whereRaw('LOWER(nama_instansi) = ?', [strtolower($request->nama_instansi)]);
        if ($request->filled('exclude_id')) $query->where('id', '!=', $request->exclude_id);
        $existing = $query->first();

        return response()->json($existing
            ? ['exists' => true, 'id' => $existing->id, 'name' => $existing->nama_instansi, 'detail' => 'ID: '.$existing->id]
            : ['exists' => false]);
    }
}