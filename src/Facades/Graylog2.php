<?php

namespace Swis\Graylog2\Facades;

use Illuminate\Support\Facades\Facade;

class Graylog2 extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'graylog2';
    }
}
