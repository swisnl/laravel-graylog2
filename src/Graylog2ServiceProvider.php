<?php

namespace Swis\Graylog2;

use Illuminate\Support\Facades\Log;
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
            __DIR__.'/../config/graylog2.php' => $this->app->configPath().'/graylog2.php',
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('graylog2', Graylog2::class);

        // Register handler
        $monoLog = Log::getMonolog();
        $monoLog->pushHandler(new Graylog2Handler(), config('graylog2.log_level', 'debug'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['graylog2'];
    }
}
