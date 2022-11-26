<?php

namespace Canopy\Ecommerce\Providers;

use Canopy\Ecommerce\Listeners\RenderingSiteMapListener;
use Canopy\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        PasswordReset::class => [

        ]
    ];
}
