<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonselingMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'sender_id',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
    

    public function session()
    {
        return $this->belongsTo(KonselingSession::class, 'session_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}