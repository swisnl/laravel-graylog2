<?php

namespace Swis\Graylog2;

use Monolog\Handler\AbstractHandler;

class Graylog2Handler extends AbstractHandler
{
    /**
     * @inheritdoc
     */
    public function handle(array $record)
    {
        try {
            $message = $record['message'];
            $loglevel = strtolower($record['level_name']);
            dd($record);

        } catch (\Exception $e) {
            Log::error('cannot log error to graylog');
        }
    }
}
