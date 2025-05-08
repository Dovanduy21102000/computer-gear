<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdated implements ShouldBroadcast
{
    public $userId;
    public $count;

    public function __construct($userId, $count)
    {
        $this->userId = $userId;
        $this->count = $count;
    }

    public function broadcastOn()
    {
        return new Channel('cart.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'CartUpdated';
    }
}
