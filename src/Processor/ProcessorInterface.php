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
     * @param mixed           $context
     *
     * @return Message
     */
    public function process($message, $exception, $context);
}
