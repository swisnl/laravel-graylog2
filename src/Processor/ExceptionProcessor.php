<?php

namespace Swis\Graylog2\Processor;

class ExceptionProcessor implements ProcessorInterface
{
    /**
     * Process the message and exception when present.
     *
     * @param \Gelf\Message   $message
     * @param \Exception|null $exception
     * @param mixed           $context
     *
     * @return mixed
     */
    public function process($message, $exception, $context)
    {
        // Don't process the log when there is no Exception
        if ($exception === null) {
            return $message;
        }

        $message->setLine($exception->getLine());
        $message->setFile($exception->getFile());

        // Check if we want the full stack trace in the message
        if (!config('graylog2.stack_trace_in_full_message', false)) {
            return $message;
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

        return $message;
    }
}
