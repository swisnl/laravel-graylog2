<?php

namespace Swis\Graylog2\Processor;

use Illuminate\Http\Request;

class RequestProcessor implements ProcessorInterface
{
    /**
     * Process the message and exception when present.
     *
     * @param \Gelf\Message   $message
     * @param \Exception|null $exception
     *
     * @return mixed
     */
    public function process($message, $exception)
    {
        // Don't process when the setting is off
        if (!config('graylog2.log_requests', false)) {
            return $message;
        }

        // Check for a request in the context or a request from Laravel

        /** @var Request $request */
        $request = null;
        if (!empty($context['request']) && $context['request'] instanceof Request) {
            $request = $context['request'];
        } else {
            $request = app('Illuminate\Http\Request');
        }

        if (!$request) {
            return $message;
        }

        return $message
                ->setAdditional('request_url', $request->url())
                ->setAdditional('request_method', $request->method())
                ->setAdditional('request_ip', $request->ip());
    }
}
