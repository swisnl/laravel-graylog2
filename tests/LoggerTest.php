<?php


class LoggerTest extends AbstractTest
{
    /**
     * Test a simple log message.
     */
    public function testSimplePreparation()
    {
        $logger = new \Swis\Graylog2\Logger();

        $message = $logger->prepareLog('emergency', 'Test Message', ['a' => true])->toArray();

        // Ignore timestamp
        unset($message['timestamp']);

        // Ignore host (we don't know the host on Travis)
        unset($message['host']);

        $this->assertEquals(
            [
                'version'       => '1.0',
                'short_message' => 'Test Message',
                'level'         => 0,
                '_a'            => 'true',
            ],
            $message
        );
    }

    /**
     * Test the preparation of an exception.
     */
    public function testExceptionPreparation()
    {
        $logger = new \Swis\Graylog2\Logger();

        $exception = new \Exception('Test', 300);

        $message = $logger->prepareLog('emergency', 'Test Message', ['exception' => $exception])->toArray();

        $this->assertArrayHasKey('file', $message);
        $this->assertEquals('Test Message', $message['short_message']);
        $this->assertEquals('37', $message['line']);
    }
}
