<?php

namespace Swis\Graylog2;

use Illuminate\Support\ServiceProvider;

class Graylog2ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'../config/graylog2.php' => $this->app->configPath().'/graylog2.php',
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $monoLog = Log::getMonolog();
        $monoLog->pushHandler();
    }
}
