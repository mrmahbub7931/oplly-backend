<?php

namespace Canopy\PluginManagement\Providers;

use Canopy\PluginManagement\Commands\PluginActivateAllCommand;
use Canopy\PluginManagement\Commands\PluginActivateCommand;
use Canopy\PluginManagement\Commands\PluginAssetsPublishCommand;
use Canopy\PluginManagement\Commands\PluginDeactivateCommand;
use Canopy\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginDeactivateCommand::class,
            PluginRemoveCommand::class,
            PluginActivateAllCommand::class,
        ]);
    }
}
