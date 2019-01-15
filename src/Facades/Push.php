<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Push\Facades;

use Illuminate\Support\Facades\Route;
use Newestapps\Push\Http\Middlewares\MobileRequest;

class Push
{

    public static function routes()
    {
        return Route::prefix('push/')
            ->middleware(['auth:api'])
            ->as('nw-push::')
            ->group(__DIR__.'/../../routes/push-routes.php');
    }

}