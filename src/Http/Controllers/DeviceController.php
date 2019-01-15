<?php

namespace Newestapps\Push\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Response\DownstreamResponse;
use Newestapps\Push\Enum\DeviceHeader;
use Newestapps\Push\Enum\OS;
use Newestapps\Push\Events\DeviceRegistered;
use Newestapps\Push\Exception\DeviceNotFoundException;
use Newestapps\Push\Exception\MissingDeviceException;
use Newestapps\Push\Exception\UUIDException;
use Newestapps\Push\Models\Device;
use NotificationChannels\Apn\FeedbackService;
use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Newestapps\Push\Repositories\DeviceRepository;
use Newestapps\Core\Http\Controllers\ManagedController;
use Newestapps\Core\Http\Resources\ApiResponse;
use Newestapps\Core\Facades\Newestapps;

class DeviceController extends ManagedController
{

    /**
     * @var DeviceRepository
     */
    protected $repository;

    /**
     * @var LaravelValidator
     */
    private $validator;

    protected $transformer = \Newestapps\Push\Transformers\DeviceTransformer::class;

    /**
     * RolesController constructor.
     *
     * @param DeviceRepository $repository
     * @param LaravelValidator $validator
     */
    public function __construct(DeviceRepository $repository, LaravelValidator $validator)
    {
        parent::__construct();

        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function registerDevice(Request $request)
    {
        $this->validate($request, [
            'push_code' => 'required',
            'device_os' => 'required|in:'.implode(',', OS::toArray()),
            'device_os_version' => 'required',
            'app_version' => 'required|numeric',
        ]);

        $user = $request->user();
        $headers = $request->headers->all();

        if (isset($headers[DeviceHeader::X_DEVICE_ID]) && is_array($headers[DeviceHeader::X_DEVICE_ID])) {
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
            ->uuid($headers[DeviceHeader::X_DEVICE_ID])
            ->first();

        if ($device === null) {
            $device = new Device();
            $device->owner_type = get_class($user);
            $device->owner_id = $user->id;
            $device->uuid = $headers[DeviceHeader::X_DEVICE_ID];
        }

        $device->push_code = $request->get('push_code');
        $device->app_version = $request->get('app_version');
        $device->device_os = $request->get('device_os');
        $device->device_os_version = $request->get('device_os_version');
        $device->enabled = true;
        $device->save();

        Event::fire(new DeviceRegistered($device));

        if ($device->device_os == 'IOS') {
            $apnConn = config('broadcasting.connections.apn');
            if (!empty($apnConn)) {
                $feedbackService = app(FeedbackService::class);

                /** @var ApnFeedback $feedback */
                foreach ($feedbackService->get() as $feedback) {
                    Device::where('push_code', $feedback->token)
                        ->whereDeviceOs('IOS')
                        ->whereOwnerType(get_class($user))
                        ->whereOwnerId($user->id)
                        ->update([
                            'push_code' => null,
                            'enabled' => false,
                        ]);
                }
            }

            
            
            return Newestapps::apiResponse(null, "Dispositivo registrado!", 200);
        }

        if ($device->device_os == 'ANDROID') {

            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(10);

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['user_id' => $user->id]);

            $option = $optionBuilder->build();
            $data = $dataBuilder->build();

            /** @var DownstreamResponse $downstreamResponse */
            $downstreamResponse = FCM::sendTo([
                $device->push_code,
            ], $option, null, $data);

            // DELETE DEVICES
            $delete = array_merge(
                $downstreamResponse->tokensToDelete()
            );

            if (count($delete) > 0) {
                $toDelete = Device::whereIn('push_code', $delete)
                    ->delete();
            }

            // UPDATE TOKENS
            $update = $downstreamResponse->tokensToModify();
            if (count($update) > 0) {
                $toUpdate = Device::whereIn('push_code', array_keys($update))
                    ->get();

                foreach ($toUpdate as $tu) {
                    $tu->push_code = $update[$tu];
                    $tu->save();
                }
            }

            if ($downstreamResponse->numberSuccess() == 1) {
                return Newestapps::apiResponse(null, "Dispositivo registrado!", 200);
            }

            if ($downstreamResponse->numberFailure() > 0) {
                return Newestapps::apiErrorResponse(null, "Falha ao registrar o dispositivo!", 400);
            }

        }
    }

//
//    public function store(Request $request)
//    {
//        try {
//            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
//            $device = $this->repository->create($request->all());
//
//            $message = 'Device created.';
//
//            if ($request->wantsJson()) {
//                return $this->itemResponse($device, $this->transformer, $message);
//            }
//
//            return redirect()->back()->with('message', $message);
//
//        } catch (ValidatorException $e) {
//
//            if ($request->wantsJson()) {
//                return Newestapps::apiErrorResponse($e->getMessageBag());
//            }
//
//            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
//        }
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param Request $request
//     * @param string $id
//     *
//     * @return ApiResponse|\Newestapps\Core\Http\Resources\ApiErrorResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
//     */
//    public function update(Request $request, $id)
//    {
//        try {
//            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
//            $device = $this->repository->update($request->all(), $id);
//
//            $message = 'Device updated.';
//
//            if ($request->wantsJson()) {
//                return $this->itemResponse($device);
//            }
//
//            return redirect()->back()->with('message', $message);
//
//        } catch (ValidatorException $e) {
//
//            if ($request->wantsJson()) {
//                return Newestapps::apiErrorResponse($e->getMessageBag());
//            }
//
//            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
//        }
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param Request $request
//     * @param int $id
//     *
//     * @return ApiResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
//     */
//    public function destroy(Request $request, $id)
//    {
//        $deleted = $this->repository->delete($id);
//
//        $message = 'Device deleted.';
//
//        if ($request->wantsJson()) {
//            return Newestapps::apiResponse($deleted, $message);
//        }
//
//        return redirect()->back()->with('message', $message);
//    }

}
