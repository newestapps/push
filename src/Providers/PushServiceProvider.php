<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Push\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;

class PushServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/nw-push.php', 'nw-push');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->app->make(Factory::class)->load(__DIR__.'/../../database/factories.php');

        $this->publishes([
            __DIR__.'/../../config/nw-push.php' => config_path('nw-push.php'),
        ], 'newestapps/push');
    }

}