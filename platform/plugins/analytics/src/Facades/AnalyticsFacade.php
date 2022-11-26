<?php

namespace Canopy\Analytics\Facades;

use Canopy\Analytics\Analytics;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Canopy\Analytics\Analytics
 */
class AnalyticsFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Analytics::class;
    }
}
