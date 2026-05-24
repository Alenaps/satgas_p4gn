<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonselorProfile extends Model
{
    use HasFactory;

    protected $table = 'konselor_profiles'; // Tambahkan ini untuk memastikan

    protected $fillable = [
        'user_id', 'nomor_lisensi', 'spesialisasi', 'pengalaman_kerja',
        'pendidikan_terakhir', 'sertifikasi_P4GN', 'bio_singkat',
        'id_instansi', 'id_jabatan',
    ];

    protected $casts = [
        'sertifikasi_P4GN' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }
}