<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class scannedNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member_id;

    public function __construct($member_id)
    {
        $this->member_id = $member_id;
    }

    public function broadcastOn()
    {
        return ['my-channel-' . $this->member_id];
    }

    public function broadcastAs()
    {
        return 'alert';
    }
}
