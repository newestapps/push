<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Push\Traits;

use \Newestapps\Push\Models\Device;

trait HasDevices
{

    public function devices()
    {
        $this->hasMany(Device::class, 'owner');
    }

    public function addDevice(Device $device){
        dd($device);
    }

}