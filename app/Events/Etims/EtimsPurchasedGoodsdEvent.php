<?php

namespace App\Events\Etims;

use App\Models\EtimsPurchase;
use App\Models\EtimsPurchaseItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EtimsPurchasedGoodsdEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $product, $etimsPurchaseItem, $acceptedQuantity, $staff;
    /**
     * Create a new event instance.
     */
    public function __construct(EtimsPurchaseItem $etimsPurchaseItem, Product $product, Float $acceptedQuantity, User $staff)
    {
        $this->etimsPurchaseItem = $etimsPurchaseItem;
        $this->product= $product;
        $this->staff= $staff;
        $this->acceptedQuantity = $acceptedQuantity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
