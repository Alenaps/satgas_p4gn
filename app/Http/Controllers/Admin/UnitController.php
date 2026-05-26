<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();
        if ($request->filled('search'))
            $query->where('nama_unit', 'like', '%'.$request->search.'%');
        if ($request->filled('kategori') && in_array($request->kategori, ['Akademik','Administrasi']))
            $query->where('kategori_unit', $request->kategori);

        return view('admin.unit.index', [
            'units' => $query->latest()->paginate(10)->withQueryString()
        ]);
    }

    public function create()
    {
        return view('admin.unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unit'     => 'required|string|max:255',
            'kategori_unit' => 'required|in:Akademik,Administrasi',
        ]);

        if ($request->filled('force_overwrite') && $request->filled('overwrite_id')) {
            $unit = Unit::findOrFail($request->overwrite_id);
            $unit->update($request->only('nama_unit', 'kategori_unit'));
            return redirect()->route('admin.unit.index')
                ->with('success', 'Data unit berhasil ditimpa/diperbarui.');
        }

        Unit::create($request->only('nama_unit', 'kategori_unit'));
        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        return view('admin.unit.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'nama_unit'     => 'required|string|max:255',
            'kategori_unit' => 'required|in:Akademik,Administrasi',
        ]);

        if ($request->filled('force_overwrite') && $request->filled('overwrite_id')
            && $request->overwrite_id != $unit->id) {
            $other = Unit::findOrFail($request->overwrite_id);
            $other->update($request->only('nama_unit', 'kategori_unit'));
            $unit->delete();
            return redirect()->route('admin.unit.index')
                ->with('success', 'Data unit berhasil ditimpa. Record duplikat dihapus.');
        }

        $unit->update($request->only('nama_unit', 'kategori_unit'));
        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.unit.index')->with('success', 'Unit berhasil dihapus.');
    }

    /** AJAX: cek duplikasi nama unit */
    public function checkDuplicate(Request $request)
    {
        $query = Unit::whereRaw('LOWER(nama_unit) = ?', [strtolower($request->nama_unit)]);
        if ($request->filled('exclude_id'))
            $query->where('id', '!=', $request->exclude_id);

        $existing = $query->first();

        if ($existing) {
            return response()->json([
                'exists' => true,
                'id'     => $existing->id,
                'name'   => $existing->nama_unit,
                'detail' => 'Kategori: ' . $existing->kategori_unit,
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Download template CSV — hanya header, tanpa data contoh.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_unit.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // Hanya header, tidak ada data contoh
            fputcsv($file, ['nama_unit', 'kategori_unit']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import CSV utama.
     */
    public function import(Request $request)
    {
        $request->validate([
            // Terima berbagai MIME type CSV dari berbagai OS/browser
            'file_import' => 'required|file|max:2048|mimes:csv,txt',
        ]);

        try {
            $path = $request->file('file_import')->getRealPath();
            $file = fopen($path, 'r');

            // Buang BOM jika ada (UTF-8 BOM dari Excel)
            $bom = fread($file, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($file);
            }

            $imported   = 0;
            $skipped    = 0;
            $errors     = [];
            $duplicates = [];
            $rowNum     = 0;

            while (($row = fgetcsv($file)) !== false) {
                $rowNum++;
                if ($rowNum === 1) continue; // skip header

                $nama = isset($row[0]) ? trim($row[0]) : '';
                $kat  = isset($row[1]) ? trim($row[1]) : '';

                // Lewati baris benar-benar kosong
                if ($nama === '' && $kat === '') continue;

                if ($nama === '') {
                    $errors[] = "Baris {$rowNum}: nama_unit kosong.";
                    continue;
                }

                if (!in_array($kat, ['Akademik', 'Administrasi'])) {
                    $errors[] = "Baris {$rowNum}: kategori '{$kat}' tidak valid (harus Akademik atau Administrasi).";
                    continue;
                }

                if ($imported + $skipped >= 500) {
                    $errors[] = "Batas 500 baris tercapai di baris {$rowNum}.";
                    break;
                }

                // Cek duplikat
                $existing = Unit::whereRaw('LOWER(nama_unit) = ?', [strtolower($nama)])->first();

                if ($existing) {
                    // Kumpulkan info duplikat → minta konfirmasi user
                    $duplicates[] = [
                        'row'      => $rowNum,
                        'id'       => $existing->id,      // untuk link Edit Manual
                        'nama'     => $nama,
                        'kat_baru' => $kat,
                        'kat_lama' => $existing->kategori_unit,
                    ];
                    $skipped++;
                } else {
                    Unit::create(['nama_unit' => $nama, 'kategori_unit' => $kat]);
                    $imported++;
                }
            }

            fclose($file);

            // Ada duplikat → simpan file tmp, redirect ke create dengan info duplikat
            if (!empty($duplicates)) {
                $tmpPath = $request->file('file_import')->store('imports_tmp');

                return redirect()->route('admin.unit.create')
                    ->with('_tab', 'import')
                    ->with('import_duplicates', $duplicates)
                    ->with('import_tmp_path', $tmpPath)
                    ->with('import_imported_so_far', $imported)
                    ->with('import_errors', $errors);
            }

            $msg = "Import selesai: {$imported} unit berhasil ditambahkan.";

            if (!empty($errors)) {
                return redirect()->route('admin.unit.create')
                    ->with('_tab', 'import')
                    ->with('success', $msg)
                    ->with('import_errors', $errors);
            }

            return redirect()->route('admin.unit.index')->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->route('admin.unit.create')
                ->with('_tab', 'import')
                ->with('import_errors', ['Gagal membaca file: ' . $e->getMessage()]);
        }
    }

    /**
     * Konfirmasi import setelah user memilih overwrite/skip untuk duplikat.
     */
    public function importConfirm(Request $request)
    {
        $tmpPath = $request->input('tmp_path');
        $action  = $request->input('action'); // 'overwrite' atau 'skip'

        if (!$tmpPath || !Storage::exists($tmpPath)) {
            return redirect()->route('admin.unit.create')
                ->with('_tab', 'import')
                ->with('import_errors', ['File sementara tidak ditemukan, silakan upload ulang.']);
        }

        $fullPath = Storage::path($tmpPath);
        $file     = fopen($fullPath, 'r');

        // Buang BOM jika ada
        $bom = fread($file, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($file);
        }

        $imported = 0;
        $skipped  = 0;
        $rowNum   = 0;

        while (($row = fgetcsv($file)) !== false) {
            $rowNum++;
            if ($rowNum === 1) continue;

            $nama = isset($row[0]) ? trim($row[0]) : '';
            $kat  = isset($row[1]) ? trim($row[1]) : '';

            if ($nama === '' && $kat === '') continue;
            if ($nama === '') continue;
            if (!in_array($kat, ['Akademik', 'Administrasi'])) continue;

            $existing = Unit::whereRaw('LOWER(nama_unit) = ?', [strtolower($nama)])->first();

            if ($existing) {
                if ($action === 'overwrite') {
                    $existing->update(['nama_unit' => $nama, 'kategori_unit' => $kat]);
                    $imported++;
                } else {
                    $skipped++;
                }
            } else {
                // Data non-duplikat sudah diimport di langkah pertama, lewati
                // (atau bisa diimport ulang dengan aman karena create baru)
                Unit::firstOrCreate(
                    ['nama_unit' => $nama],
                    ['kategori_unit' => $kat]
                );
                $imported++;
            }
        }

        fclose($file);
        Storage::delete($tmpPath);

        $msg = "Import selesai: {$imported} unit ditambahkan/diperbarui.";
        if ($skipped) $msg .= " {$skipped} data duplikat dilewati.";

        return redirect()->route('admin.unit.index')->with('success', $msg);
    }
}