<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public Message $message;
    public int $sessionId;

    public function __construct(Message $message, int $sessionId)
    {
        $this->message = $message->load('sender');
        $this->sessionId = $sessionId;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('konseling.' . $this->sessionId);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
