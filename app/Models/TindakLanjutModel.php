<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjutModel extends Model
{
   protected $table = 'tindak_lanjuts';
   
   protected $fillable = ['laporan_id', 'status', 'catatan', 'admin_id'];
    public function laporan()
    {
        return $this->belongsTo(LaporanModel::class);
    }
}
