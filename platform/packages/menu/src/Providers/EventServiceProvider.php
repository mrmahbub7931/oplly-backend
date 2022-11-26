<?php

namespace Canopy\Menu\Providers;

use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Menu\Listeners\DeleteMenuNodeListener;
use Canopy\Menu\Listeners\UpdateMenuNodeUrlListener;
use Canopy\Slug\Events\UpdatedSlugEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatedSlugEvent::class    => [
            UpdateMenuNodeUrlListener::class,
        ],
        DeletedContentEvent::class => [
            DeleteMenuNodeListener::class,
        ],
    ];
}
