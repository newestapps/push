<?php

namespace Newestapps\Push\Repositories\DataSource;

use App\User;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;
use Newestapps\Core\Repositories\CoreBaseRepository;
use Newestapps\Push\Repositories\DeviceRepository;
use Newestapps\Push\Models\Device;

/**
 * Class DeviceRepositoryEloquent.
 *
 * @package namespace App\Repositories\DataSource;
 */
class DeviceRepositoryEloquent extends CoreBaseRepository implements DeviceRepository
{

    /**
     * Validation rules for this repository
     *
     * @return array
     */
    function getRules()
    {
        return [
            ValidatorInterface::RULE_CREATE => [
                'id' => 'nullable',
                'uuid' => 'nullable',
                'owner_type' => 'nullable',
                'owner_id' => 'nullable',
                'push_code' => 'nullable',
                'device_os' => 'nullable',
                'device_os_version' => 'nullable',
                'app_version' => 'nullable',
                'created_at' => 'nullable',
                'updated_at' => 'nullable',
            ],

            ValidatorInterface::RULE_UPDATE => [
                'id' => 'nullable',
                'uuid' => 'nullable',
                'owner_type' => 'nullable',
                'owner_id' => 'nullable',
                'push_code' => 'nullable',
                'device_os' => 'nullable',
                'device_os_version' => 'nullable',
                'app_version' => 'nullable',
                'created_at' => 'nullable',
                'updated_at' => 'nullable',
            ],
        ];
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Device::class;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    function registerDevice(User $user, Device $device)
    {
        // TODO: Implement registerDevice() method.
    }


}
