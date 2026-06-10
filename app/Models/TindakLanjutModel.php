<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjutModel extends Model
{
   protected $table = 'tindak_lanjuts';
   
   protected $fillable = ['laporan_id', 'status', 'catatan', 'admin_id', 'konselor_id'];
    public function laporan()
    {
        return $this->belongsTo(LaporanModel::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    public function konselor()
    {
        return $this->belongsTo(User::class, 'konselor_id');
    }   
}
