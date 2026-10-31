<?php

namespace App\Events\Etims;

use App\Models\ImportItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EtimsImportedGoodCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product, $etimsImportedItem, $acceptedQuantity, $staff;
    /**
     * Create a new event instance.
     */
    public function __construct(ImportItem $etimsImportedItem, Product $product, Float $acceptedQuantity, User $staff)
    {
        $this->etimsImportedItem = $etimsImportedItem;
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
