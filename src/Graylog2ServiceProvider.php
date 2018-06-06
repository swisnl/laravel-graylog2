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
            __DIR__.'/../config/graylog2.php' => base_path('config/graylog2.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/graylog2.php', 'graylog2');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('graylog2', Graylog2::class);

        // Register handler
        $monoLog = $this->logger();
        $monoLog->pushHandler(new Graylog2Handler(config('graylog2.log_level', 'debug')));
    }

    /**
     * @return \Monolog\Logger
     */
    protected function logger()
    {
        return app('log');
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
