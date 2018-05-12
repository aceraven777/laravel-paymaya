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
            __DIR__.'/../library/User.php' => app_path('Libraries/PayMaya/User.php'),
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
