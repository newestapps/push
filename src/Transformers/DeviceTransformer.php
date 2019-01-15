<?php

namespace Newestapps\Push\Transformers;

use League\Fractal\TransformerAbstract;
use Newestapps\Core\Transformers\CoreTransformer;

/**
 * Class DeviceTransformer.
 *
 * @package namespace App\Transformers;
 */
class DeviceTransformer extends CoreTransformer
{

    protected $availableIncludes = [];

    /**
     * Transform the Device entity.
     *
     * @param \App\Device $model
     *
     * @return array
     */
    public function transform(Device $model = null)
    {
        return [
            'id' => $model->id,
            'uuid' => $model->uuid,
            'owner_type' => $model->owner_type,
            'owner_id' => $model->owner_id,
            'push_code' => $model->push_code,
            'device_os' => $model->device_os,
            'device_os_version' => $model->device_os_version,
            'app_version' => $model->app_version,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }

}
