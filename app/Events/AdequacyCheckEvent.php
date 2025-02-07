<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdequacyCheckEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $alertData;

    public function __construct(array $alertData)
    {
        $this->alertData = $alertData;
    }

    public function broadcastOn()
    {
        // Broadcast on the same “alerts” channel.
        return new Channel('alerts');
    }
    public function broadcastAs()
    {
        return 'AdequacyCheckEvent';
    }
}
