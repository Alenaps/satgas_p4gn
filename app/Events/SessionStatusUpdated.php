<?php

namespace App\Events;

use App\Models\KonselingSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public KonselingSession $session;

    public function __construct(KonselingSession $session)
    {
        // Load BOTH relationships — konseli (untuk sisi konselor) dan konselor (untuk sisi konsuli)
        $this->session = $session->load('konseli', 'konselor');
    }

    public function broadcastOn(): array
    {
        return [
            // Untuk update di halaman konselor (sudah ada sebelumnya)
            new PrivateChannel('konselor.' . $this->session->konselor_id),

            // ← TAMBAHAN: Untuk update realtime di halaman konsuli (index & daftar konselor)
            new PrivateChannel('konseli.' . $this->session->konseli_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'session.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id'     => $this->session->id,
            'status' => $this->session->status,

            // Data konseli (untuk sisi konselor)
            'konseli' => [
                'nama'    => $this->session->konseli->nama,
                'npm_nip' => $this->session->konseli->npm_nip,
                'foto'    => $this->session->konseli->foto
                    ? asset('storage/' . $this->session->konseli->foto)
                    : null,
            ],

            // ← TAMBAHAN: Data konselor (untuk sisi konsuli — dipakai di buildActiveCard, buildRejectedCard)
            'konselor' => [
                'id'   => $this->session->konselor->id,
                'nama' => $this->session->konselor->nama,
                'foto' => $this->session->konselor->foto
                    ? $this->session->konselor->foto  // path relatif, bukan asset() — JS pakai /storage/...
                    : null,
            ],

            // ISO string agar JS bisa new Date(s.started_at) dengan benar
            'started_at' => $this->session->started_at?->toISOString(),

            'routes' => [
                'approve'  => route('konselor.konseling.approve', $this->session->id),
                'reject'   => route('konselor.konseling.reject',  $this->session->id),
                'chat'     => route('konselor.konseling.chat',    $this->session->id),
                'end_form' => route('konselor.konseling.end-form',$this->session->id),
            ],
        ];
    }
}