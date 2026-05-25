<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanModel extends Model
{
    protected $table = 'laporans';
    protected $fillable = [
        'nama_pelapor','peran_pelapor','npm_nip','telp_pelapor','email','jk_pelapor',
        'nama_terlapor','peran_terlapor','telp_terlapor','jk_terlapor','alamat_terlapor',
        'lokasi','foto_lokasi','tanggal','jenis_narkoba_id','kronologi', 'jenis_kasus',
        
         // tracking
        'kode_laporan', 'token_laporan', 'status','user_id'
    ];

    public function jenis_narkoba()
    {
        return $this->belongsTo(JenisNarkoba::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjutModel::class, 'laporan_id');
    }
}
