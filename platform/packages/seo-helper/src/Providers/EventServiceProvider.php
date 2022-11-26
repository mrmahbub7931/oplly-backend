<?php

namespace Canopy\SeoHelper\Providers;

use Canopy\Base\Events\CreatedContentEvent;
use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Events\UpdatedContentEvent;
use Canopy\SeoHelper\Listeners\CreatedContentListener;
use Canopy\SeoHelper\Listeners\DeletedContentListener;
use Canopy\SeoHelper\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
    ];
}
