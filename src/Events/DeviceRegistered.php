<?php

namespace Newestapps\Push\Events;

use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\Request;
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

    /** @var Device */
    private $device;

    /** @var Request */
    private $request;

    private $newDevice;

    public function __construct(Device $device, Request $request, $newDevice = false)
    {
        $this->device = $device;
        $this->request = $request;
        $this->newDevice = $newDevice;
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

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    public function isNewDevice()
    {
        return $this->newDevice;
    }

}
