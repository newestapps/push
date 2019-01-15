<?php

namespace Newestapps\Push\Events;

use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Newestapps\Push\Models\Device;

class DeviceRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('NW:Push::DeviceRegistered');
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

}
