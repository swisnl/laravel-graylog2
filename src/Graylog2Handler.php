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
        try {
            /** @var Graylog2 $graylog2 */
            $graylog2 = app('graylog2');

            $graylog2->log(
                strtolower($record['level_name']),
                $record['message'],
                array_merge(
                    $record['context'],
                    [
                        'request' => app('Illuminate\Http\Request'),
                        'extra'   => $record['extra'],
                        'channel' => $record['channel'],
                    ]
                )
            );
        } catch (\Exception $e) {
            Log::error('cannot log error to graylog');
        }
    }
}
