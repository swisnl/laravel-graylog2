<?php

abstract class AbstractTest extends Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Import default settings
        $defaultGraylog2Settings = require __DIR__.'/../config/graylog2.php';
        $app['config']->set('graylog2', $defaultGraylog2Settings);
    }

    protected function getPackageProviders($app)
    {
        return ['Swis\Graylog2\Graylog2ServiceProvider'];
    }
}
