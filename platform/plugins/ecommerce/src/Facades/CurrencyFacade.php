<?php

namespace Canopy\Ecommerce\Facades;

use Canopy\Ecommerce\Supports\CurrencySupport;
use Illuminate\Support\Facades\Facade;

class CurrencyFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CurrencySupport::class;
    }
}
