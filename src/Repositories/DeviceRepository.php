<?php

namespace Newestapps\Push\Repositories;

use App\User;
use Newestapps\Push\Models\Device;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DeviceRepository.
 *
 * @method pushCriteria($app);
 *
 * @package namespace App\Repositories;
 */
interface DeviceRepository extends RepositoryInterface
{

    function registerDevice(User $user, Device $device);

}
