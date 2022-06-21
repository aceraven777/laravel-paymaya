<?php

namespace Aceraven777\PayMaya;

use Illuminate\Support\ServiceProvider;

class PayMayaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../libraries/User.php' => app_path('Libraries/PayMaya/User.php'),
            __DIR__.'/../config/paymaya.php' => config_path('paymaya.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
