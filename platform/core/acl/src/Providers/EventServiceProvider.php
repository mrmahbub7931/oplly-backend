<?php

namespace Canopy\ACL\Providers;

use Canopy\ACL\Events\RoleAssignmentEvent;
use Canopy\ACL\Events\RoleUpdateEvent;
use Canopy\ACL\Listeners\LoginListener;
use Canopy\ACL\Listeners\RoleAssignmentListener;
use Canopy\ACL\Listeners\RoleUpdateListener;
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
        RoleUpdateEvent::class     => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        Login::class               => [
            LoginListener::class,
        ],
    ];
}
