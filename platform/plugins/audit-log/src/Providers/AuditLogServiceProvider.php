<?php

namespace Canopy\AuditLog\Providers;

use Canopy\AuditLog\Facades\AuditLogFacade;
use Canopy\AuditLog\Models\AuditHistory;
use Canopy\AuditLog\Repositories\Caches\AuditLogCacheDecorator;
use Canopy\AuditLog\Repositories\Eloquent\AuditLogRepository;
use Canopy\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Canopy\Base\Supports\Helper;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:05 AM
 */
class AuditLogServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(AuditLogInterface::class, function () {
            return new AuditLogCacheDecorator(new AuditLogRepository(new AuditHistory));
        });

        AliasLoader::getInstance()->alias('AuditLog', AuditLogFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);

        $this->setNamespace('plugins/audit-log')
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugin-audit-log',
                    'priority'    => 8,
                    'parent_id'   => 'cms-core-platform-administration',
                    'name'        => 'plugins/audit-log::history.name',
                    'icon'        => null,
                    'url'         => route('audit-log.index'),
                    'permissions' => ['audit-log.index'],
                ]);
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
