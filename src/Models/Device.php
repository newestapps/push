<?php

namespace Newestapps\Push\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Device
 */
class Device extends Model
{

    protected $fillable = [
        'owner_type',
        'owner_id',
        'push_code',
        'device_os',
        'device_os_version',
        'app_version',
    ];

    protected $casts = [
    ];

    protected $hidden = [
    ];

    public function owner()
    {
        return $this->morphTo('owner');
    }

    public function scopeUser($query, Model $user)
    {
        $query->where('owner_type', get_class($user))
            ->where('owner_id', $user->id);
    }

    public function scopeUuid($query, $uuid)
    {
        $query->where('uuid', $uuid);
    }

}
