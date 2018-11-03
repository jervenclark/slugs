<?php

namespace Jervenclark\Slugs\Facades;

use Illuminate\Support\Facades\Facade;

class Slugs extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'slugs';
    }
}
