<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageController extends Controller
{
    public function serve(string $path): StreamedResponse|\Illuminate\Http\Response
    {
        // Cek apakah file ada di storage/app/public
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $file     = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        $size     = Storage::disk('public')->size($path);

        return response($file, 200, [
            'Content-Type'   => $mimeType,
            'Content-Length' => $size,
            'Cache-Control'  => 'public, max-age=86400', // cache 1 hari
        ]);
    }
}