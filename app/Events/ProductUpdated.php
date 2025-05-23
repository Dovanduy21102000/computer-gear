<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProductUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;

    /**
     * Create a new event instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
        Log::info('ProductUpdated event constructed', ['product_id' => $product->id]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        Log::info('Broadcasting on products channel', ['product_id' => $this->product->id]);
        return new Channel('products');
    }

    public function broadcastWith()
    {
        $data = [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'price_sale' => $this->product->price_sale,
            'quantity' => $this->product->quantity,
            'status' => $this->product->status,
        ];
        Log::info('Broadcasting data', $data);
        return $data;
    }
}
