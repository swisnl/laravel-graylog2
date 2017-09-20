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

        // Handle a log from Laravel
        /** @var Graylog2 $graylog2 */
        $graylog2 = app('graylog2');

        try {
            $graylog2->log(
                strtolower($record['level_name']),
                $record['message'],
                $record['context']
            );

            return true;
        } catch (\Exception $e) {
            Log::info('Could not log to Graylog.');

            return false;
        }
    }
}
