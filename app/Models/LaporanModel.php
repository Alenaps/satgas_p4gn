<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanModel extends Model
{
    protected $table = 'laporans';
    protected $fillable = [
        'nama_pelapor','peran_pelapor','nip','telp_pelapor','email','jk_pelapor',
        'nama_terlapor','peran_terlapor','telp_terlapor','jk_terlapor','alamat_terlapor',
        'lokasi','foto_lokasi','tanggal','jenis_narkoba','kronologi'
    ];
}
