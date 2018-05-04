<?php

namespace Newestapps\Push\Http\Middlewares;

use App\Organizer;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Newestapps\Core\Facades\Newestapps;
use Newestapps\Push\Enum\DeviceHeader;
use Newestapps\Push\Exception\DeviceNotFoundException;
use Newestapps\Push\Exception\MissingDeviceException;
use Newestapps\Push\Models\Device;
use Spatie\Permission\Exceptions\UnauthorizedException;

class MobileRequest
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \Closure|Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = $request->headers->all();

        if(isset($headers[DeviceHeader::X_DEVICE_ID]) && is_array($headers[DeviceHeader::X_DEVICE_ID])){
            $headers[DeviceHeader::X_DEVICE_ID] = reset($headers[DeviceHeader::X_DEVICE_ID]);
        }

        if (!isset($headers[DeviceHeader::X_DEVICE_ID])) {
            throw new MissingDeviceException();
        }

        if (!is_uuid($headers[DeviceHeader::X_DEVICE_ID])) {
            throw new UUIDException();
        }

        $user = $request->user();
        if ($user === null) {
            throw new UnauthorizedException();
        }

        $device = Device::user($user)
            ->uuid($request->headers->get(DeviceHeader::X_DEVICE_ID))
            ->first();

        if (empty($device)) {
            throw new DeviceNotFoundException();
        }

        app()->instance(Device::class, $device);

        return $next($request);
    }

}
