<?php

namespace App\Events;

use App\Models\ProductVariant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class VariantUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $variant;

    public function __construct(ProductVariant $variant)
    {
        $this->variant = $variant;
    }

    public function broadcastOn()
    {
        return new Channel('variants');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->variant->id,
            'product_id' => $this->variant->product_id,
            'sku' => $this->variant->sku,
            'price' => $this->variant->price,
            'price_sale' => $this->variant->price_sale,
            'quantity' => $this->variant->quantity,
            'status' => $this->variant->status,
        ];
    }
}
