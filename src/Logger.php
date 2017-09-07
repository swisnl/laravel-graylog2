<?php

namespace Swis\Graylog2;

use Exception;
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
        $message = $this->initMessage($level, $rawMessage, $context);

        // add exception data if present
        if (isset($context['exception'])
            && $context['exception'] instanceof \Exception
        ) {
            $this->initExceptionData($message, $context['exception']);
        }

        return $message;
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

    /**
     * Initializes Exceptiondata with given message.
     *
     * @param Message   $message
     * @param Exception $exception
     */
    protected function initExceptionData(Message $message, Exception $exception)
    {
        $message->setLine($exception->getLine());
        $message->setFile($exception->getFile());

        if (!config('graylog2.exception-in-full-message')) {
            return;
        }

        $longText = '';
        do {
            $longText .= sprintf(
                    "%s: %s (%d)\n\n%s\n",
                    get_class($exception),
                    $exception->getMessage(),
                    $exception->getCode(),
                    $exception->getTraceAsString()
                );

            $exception = $exception->getPrevious();
        } while ($exception && $longText .= "\n--\n\n");
        $message->setFullMessage($longText);
    }
}
