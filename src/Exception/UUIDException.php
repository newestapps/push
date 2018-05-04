<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Push\Exception;

class UUIDException extends \Exception
{
    /**
     * MissingDeviceException constructor.
     */
    public function __construct()
    {
        parent::__construct("INVALID UUID");
    }
}