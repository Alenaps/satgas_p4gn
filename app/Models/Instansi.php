<?php
// app/Models/Instansi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $table = 'instansi';
    protected $fillable = ['nama_instansi'];

    public function konselorProfiles()
    {
        return $this->hasMany(KonselorProfile::class, 'id_instansi');
    }
}