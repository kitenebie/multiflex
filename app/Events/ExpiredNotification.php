<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpiredNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member_id;

    public $message;

    public function __construct($member_id, $message)
    {
        $this->member_id = $member_id;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['my-channel-' . $this->member_id];
    }

    public function broadcastAs()
    {
        return 'expired';
    }
}
