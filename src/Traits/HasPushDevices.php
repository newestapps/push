<?php
/**
 * Created by rodrigotbrun
 *   with PhpStorm
 */

namespace Newestapps\Push\Traits;

use Newestapps\Push\Models\Device;

trait HasPushDevices
{
    private static $apn = null;
    private static $gcm = null;

    public function hasAppleDevices()
    {
        return (count($this->routeNotificationForApn()) > 0);
    }

    public function hasAndroidDevices()
    {
        return (count($this->routeNotificationForGcm()) > 0);
    }

    public function routeNotificationForApn()
    {
        if (self::$apn === null) {
            self::$apn = Device::select('push_code')
                ->whereEnabled(true)
                ->whereOwnerType(get_class($this))
                ->whereOwnerId($this->attributes['id'])
                ->whereDeviceOs('IOS')
                ->where('push_code', '<>', null)
                ->get();
        }

        return self::$apn->pluck('push_code')->toArray();
    }

    public function routeNotificationForGcm()
    {
        if (self::$gcm === null) {
            self::$gcm = Device::select('push_code')
                ->whereEnabled(true)
                ->whereOwnerType(get_class($this))
                ->whereOwnerId($this->attributes['id'])
                ->whereDeviceOs('ANDROID')
                ->where('push_code', '<>', null)
                ->get();
        }

        return self::$gcm->pluck('push_code')->toArray();
    }

}