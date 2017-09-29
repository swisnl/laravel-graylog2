<?php

use Swis\Graylog2\Graylog2Handler;

class HandlerTest extends AbstractTest
{
    /**
     * Test enabling and disabling of
     * Graylog2 error reporting.
     */
    public function testEnabling()
    {
        $handler = new Graylog2Handler();
        $this->assertFalse($handler->handle([]));

        $this->app['config']->set('graylog2.enabled', false);
        $this->assertFalse($handler->handle([]));
    }
}
