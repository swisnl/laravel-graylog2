<?php

namespace Swis\Graylog2;

use Gelf\Logger as GelfLogger;
use Gelf\Message;

class Logger extends GelfLogger
{
    /**
     * Prepare a log.
     *
     * @param $message
     * @param mixed $level
     * @param mixed $rawMessage
     * @param array $context
     *
     * @return Message
     */
    public function prepareLog($level, $rawMessage, array $context = [])
    {
        return $this->initMessage($level, $rawMessage, $context);
    }

    /**
     * Publish an already constructed GELF message.
     *
     * @param Message $message
     */
    public function publishMessage(Message $message)
    {
        $this->publisher->publish($message);
    }
}
