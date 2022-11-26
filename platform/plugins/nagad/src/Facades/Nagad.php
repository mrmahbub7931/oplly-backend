<?php

namespace Canopy\Nagad\Facades;

use Illuminate\Support\Facades\Facade;

class Nagad extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
    protected static function getFacadeAccessor(): string
    {
        return 'nagad';
    }
}
