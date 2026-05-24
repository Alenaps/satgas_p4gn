<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonselingSession extends Model
{
    use HasFactory;

    protected $table = 'konseling_sessions';
    protected $fillable = [
        'konseli_id',
        'konselor_id',
        'status',
        'started_at',
        'ended_at',
        'catatan_konselor'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Method untuk mendapatkan tanggal dalam timezone Asia/Jakarta
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone('Asia/Jakarta');
    }

    public function konseli()
    {
        return $this->belongsTo(User::class, 'konseli_id');
    }

    public function konselor()
    {
        return $this->belongsTo(User::class, 'konselor_id');
    }

    public function messages()
    {
        return $this->hasMany(KonselingMessage::class, 'session_id');
    }

    public function unreadMessagesCount($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}