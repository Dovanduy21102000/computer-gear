<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckoutSessionUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $checkoutData;

    public function __construct($userId, $checkoutData)
    {
        $this->userId = $userId;
        $this->checkoutData = $checkoutData;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('checkout.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'CheckoutSessionUpdated';
    }

    public function broadcastWith()
    {
        return [
            'checkoutData' => $this->checkoutData
        ];
    }
}
