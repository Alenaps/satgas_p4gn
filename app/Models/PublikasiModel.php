<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublikasiModel extends Model
{
    use HasFactory;

    protected $table = 'publikasis';  
    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'ringkasan',
        'kutipan',
        'keyword',
        'kategori',
        'status',
        'label',
        'thumbnail',
        'user_id',
    ];

    // Auto Generate Slug ketika menambahkan judul
     public static function booted()
    {
        static::creating(function($model){
            if(empty($model->slug)){
                $model->slug = Str::slug($model->judul) . '-' . uniqid();
            }
        });
    }
}
