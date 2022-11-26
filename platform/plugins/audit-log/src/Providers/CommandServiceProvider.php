<?php

namespace Canopy\AuditLog\Providers;

use Canopy\AuditLog\Commands\ActivityLogClearCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ActivityLogClearCommand::class,
            ]);
        }
    }
}
