<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast event untuk memperbarui dashboard statistik konseling secara realtime.
 *
 * CARA PAKAI:
 * Dispatch event ini dari mana saja ketika data konseling berubah, contoh:
 *
 *   // Di KonselingSessionController, setelah approve/reject/end session:
 *   broadcast(new \App\Events\StatistikUpdated([
 *       'status'     => $session->status,      // status baru
 *       'prevStatus' => $prevStatus,            // status sebelumnya
 *   ]))->toOthers();
 *
 *   // Di KonselingSessionController::sendMessage(), setelah pesan tersimpan:
 *   broadcast(new \App\Events\StatistikUpdated([
 *       'type' => 'pesan.baru',
 *       'jam'  => now()->hour,
 *   ]))->toOthers();
 *
 * Untuk broadcast penuh (semua data diperbarui), kirim tanpa payload spesifik:
 *   broadcast(new \App\Events\StatistikUpdated())->toOthers();
 */
class StatistikUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $payload;

    /**
     * @param array $payload  Data perubahan yang ingin dikirim ke frontend.
     *                        Kosongkan jika ingin frontend menarik data segar via fetch.
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('statistik-konseling');
    }

    /**
     * Nama event Pusher.
     * Frontend mendengarkan: channel.bind('statistik.updated', ...)
     */
    public function broadcastAs(): string
    {
        return 'statistik.updated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}