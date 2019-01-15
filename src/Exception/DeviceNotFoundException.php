<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Push\Exception;

class DeviceNotFoundException extends \Exception
{
    /**
     * MissingDeviceException constructor.
     */
    public function __construct()
    {
        parent::__construct("DEVICE NOT FOUND");
    }
}