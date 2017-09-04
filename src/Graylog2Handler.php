<?php

namespace Swis\Graylog2;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractHandler;

class Graylog2Handler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(array $record)
    {
        // Check if we should send the message to Graylog2
        if (!config('graylog2.enabled')) {
            return false;
        }

        try {
            /** @var Graylog2 $graylog2 */
            $graylog2 = app('graylog2');

            $context = array_merge(
                $record['context'],
                [
                    'extra'   => $record['extra'],
                    'channel' => $record['channel'],
                ]
            );

            if (config('graylog2.log-requests')) {
                $context['request'] = app('Illuminate\Http\Request');
            }

            $graylog2->log(
                strtolower($record['level_name']),
                $record['message'],
                $context
            );
        } catch (\Exception $e) {
            Log::error('Cannot log the error to Graylog2.');
        }
    }
}
