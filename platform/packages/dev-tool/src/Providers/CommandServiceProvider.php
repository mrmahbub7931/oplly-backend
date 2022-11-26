<?php

namespace Canopy\DevTool\Providers;

use Canopy\DevTool\Commands\InstallCommand;
use Canopy\DevTool\Commands\LocaleCreateCommand;
use Canopy\DevTool\Commands\LocaleRemoveCommand;
use Canopy\DevTool\Commands\Make\ControllerMakeCommand;
use Canopy\DevTool\Commands\Make\FormMakeCommand;
use Canopy\DevTool\Commands\Make\ModelMakeCommand;
use Canopy\DevTool\Commands\Make\RepositoryMakeCommand;
use Canopy\DevTool\Commands\Make\RequestMakeCommand;
use Canopy\DevTool\Commands\Make\RouteMakeCommand;
use Canopy\DevTool\Commands\Make\TableMakeCommand;
use Canopy\DevTool\Commands\PackageCreateCommand;
use Canopy\DevTool\Commands\PackageRemoveCommand;
use Canopy\DevTool\Commands\RebuildPermissionsCommand;
use Canopy\DevTool\Commands\TestSendMailCommand;
use Canopy\DevTool\Commands\TruncateTablesCommand;
use Canopy\DevTool\Commands\PackageMakeCrudCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TableMakeCommand::class,
                ControllerMakeCommand::class,
                RouteMakeCommand::class,
                RequestMakeCommand::class,
                FormMakeCommand::class,
                ModelMakeCommand::class,
                RepositoryMakeCommand::class,
                PackageCreateCommand::class,
                PackageMakeCrudCommand::class,
                PackageRemoveCommand::class,
                InstallCommand::class,
                TestSendMailCommand::class,
                TruncateTablesCommand::class,
                RebuildPermissionsCommand::class,
                LocaleRemoveCommand::class,
                LocaleCreateCommand::class,
            ]);
        }
    }
}
