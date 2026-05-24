<?php
// app/Models/Unit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['nama_unit', 'kategori_unit'];

    public function users()
    {
        return $this->hasMany(User::class, 'unit_id');
    }
}