<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Push;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;

class Push extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'nw-push';
    }

    public static function route()
    {
        Route::group([
            'prefix' => 'devices',
            'as' => 'devices.',
        ], function () {

            Route::post('/', '');

        });
    }

}