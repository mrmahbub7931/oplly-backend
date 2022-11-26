<?php

namespace Canopy\AuditLog\Providers;

use Canopy\AuditLog\Events\AuditHandlerEvent;
use Canopy\AuditLog\Listeners\AuditHandlerListener;
use Canopy\AuditLog\Listeners\CreatedContentListener;
use Canopy\AuditLog\Listeners\DeletedContentListener;
use Canopy\AuditLog\Listeners\LoginListener;
use Canopy\AuditLog\Listeners\UpdatedContentListener;
use Canopy\Base\Events\CreatedContentEvent;
use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Events\UpdatedContentEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AuditHandlerEvent::class   => [
            AuditHandlerListener::class,
        ],
        Login::class               => [
            LoginListener::class,
        ],
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
