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
    public function process($message, $exception, $context)
    {
        // Don't process when the setting is off
        if (!config('graylog2.log_requests', false)) {
            return $message;
        }

        /** @var Request $request */
        $request = app('Illuminate\Http\Request');

        if (!$request) {
            return $message;
        }

        // Add GET data if configured
        if (config('graylog2.log_request_get_data', false)) {
            $message->setAdditional('request_get_data', json_encode($request->query()));
        }

        // Add filtered POST data if configured
        if (config('graylog2.log_request_post_data', false)) {
            $disallowedParameters = config('graylog2.disallowed_post_parameters', []);
            $filteredParameters = array_filter(
                $request->request->all(),
                function ($key) use ($disallowedParameters) {
                    return !in_array($key, $disallowedParameters);
                },
                ARRAY_FILTER_USE_KEY
            );

            $message->setAdditional('request_post_data', json_encode($filteredParameters));
        }

        return $message
                ->setAdditional('request_url', $request->url())
                ->setAdditional('request_method', $request->method())
                ->setAdditional('request_ip', $request->ip());
    }
}
