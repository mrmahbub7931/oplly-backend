<?php

namespace Canopy\Backup\Providers;

use Canopy\Backup\Commands\BackupCreateCommand;
use Canopy\Backup\Commands\BackupListCommand;
use Canopy\Backup\Commands\BackupRemoveCommand;
use Canopy\Backup\Commands\BackupRestoreCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BackupCreateCommand::class,
                BackupRestoreCommand::class,
                BackupRemoveCommand::class,
                BackupListCommand::class,
            ]);
        }
    }
}
