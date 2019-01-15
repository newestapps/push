<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Push\Exception;

class MissingDeviceException extends \Exception
{
    /**
     * MissingDeviceException constructor.
     */
    public function __construct()
    {
        parent::__construct("MISSING DEVICE ID");
    }
}