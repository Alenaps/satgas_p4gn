<?php
// app/Models/StatusSivitas.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusSivitas extends Model
{
    protected $table = 'status_sivitas';
    protected $fillable = ['nama'];

    public function users()
    {
        return $this->hasMany(User::class, 'status_sivitas_id');
    }
}