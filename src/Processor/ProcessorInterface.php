<?php

namespace Swis\Graylog2\Processor;

use Gelf\Message;

interface ProcessorInterface
{
    /**
     * Process the message and exception when present.
     *
     * @param Message         $message
     * @param \Exception|null $exception
     *
     * @return Message
     */
    public function process($message, $exception);
}
