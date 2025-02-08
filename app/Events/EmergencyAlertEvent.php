<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmergencyAlertEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $alertData;

    /**
     * Create a new event instance.
     *
     * @param array $alertData
     * @return void
     */
    public function __construct(array $alertData)
    {
        $this->alertData = $alertData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        // Using a public channel “alerts”. (For sensitive data, consider a private channel.)
        return new Channel('alerts');
    }
    public function broadcastAs()
    {
        return 'EmergencyAlertEvent';
    }
}
