<?php

namespace Canopy\SocialLogin\Facades;

use Canopy\SocialLogin\Supports\SocialService;
use Illuminate\Support\Facades\Facade;

class SocialServiceFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SocialService::class;
    }
}
