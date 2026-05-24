<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublikasiModel;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = PublikasiModel::where('status', 'Publish');

        //search
        if ($request->q) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->q.'%')
                ->orWhere('ringkasan', 'like', '%'.$request->q.'%');
            });
        }
        //filter
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $publikasi = $query->orderBy('created_at', 'desc')
                        ->paginate(9)
                        ->withQueryString();

        $kategori = PublikasiModel::select('kategori')->distinct()->get();

        return view('guest.publikasi.index', compact('publikasi','kategori'));
    }

    public function show(PublikasiModel $publikasi)
    {
        $related = PublikasiModel::where('kategori',$publikasi->kategori)
            ->where('id','!=',$publikasi->id)
            ->orderBy('created_at','desc')
            ->limit(3)
            ->get();

        $next = PublikasiModel::where('id','>',$publikasi->id)->orderBy('id')->first();
        $prev = PublikasiModel::where('id','<',$publikasi->id)->orderBy('id','desc')->first();


        return view('guest.publikasi.show', compact('publikasi','related','next','prev'));
    }

}
