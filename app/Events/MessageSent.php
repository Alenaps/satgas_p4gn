<?php
namespace App\Events;

use App\Models\KonselingMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// 2. UBAH IMPLEMENTS INI:
class MessageSent implements ShouldBroadcastNow 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    // ... sisa kodemu di bawahnya biarkan sama ...

    public KonselingMessage $message;

    public function __construct(KonselingMessage $message)
    {
        // Load relasi sender agar tersedia di frontend
        $this->message = $message->load('sender');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('session.' . $this->message->session_id),
        ];
    }

    /**
     * Nama event yang didengar frontend
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Data yang dikirim ke frontend
     */
    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'session_id' => $this->message->session_id,
            'sender_id'  => $this->message->sender_id,
            'message'    => $this->message->message,
            'is_read'    => $this->message->is_read,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'sender'     => [
                'id'   => $this->message->sender->id,
                'nama' => $this->message->sender->nama,
            ],
        ];
    }
}