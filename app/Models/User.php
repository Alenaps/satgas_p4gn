<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail; // verifikasi ini seng 
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail 
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'npm_nip',
        'no_telp',
        'status_sivitas_id', 
        'unit_id',           
        'email',
        'password',
        'role',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke tabel status_sivitas
    public function statusSivitas()
    {
        return $this->belongsTo(StatusSivitas::class, 'status_sivitas_id');
    }

    // Relasi ke tabel units
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function konselorProfile()
    {
        return $this->hasOne(KonselorProfile::class);
    }

    //mutator untuk meng-hash password saat disimpan
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    // Override notifikasi reset password bawaan.
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
 
    // Override notifikasi verifikasi email bawaan.
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification());
    }
}