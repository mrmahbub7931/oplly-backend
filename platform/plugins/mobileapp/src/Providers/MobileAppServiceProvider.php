<?php

namespace Canopy\MobileApp\Providers;

use Canopy\Base\Supports\Helper;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Canopy\MobileApp\Models\MobileApp;
use Canopy\MobileApp\Repositories\Caches\MobileAppCacheDecorator;
use Canopy\MobileApp\Repositories\Eloquent\MobileAppRepository;
use Canopy\MobileApp\Repositories\Interfaces\MobileAppInterface;
use EmailHandler;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class MobileAppServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->singleton(MobileAppInterface::class, function () {
            return new MobileAppCacheDecorator(
                new MobileAppRepository(new MobileApp)
            );
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/mobileapp')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadMigrations();

        $this->app->register(EventServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-mobileapp',
                'priority'    => 6,
                'parent_id'   => null,
                'name'        => 'plugins/mobileapp::mobileapp.name',
                'icon'        => 'far fa-newspaper',
                'url'         => route('mobileapp.settings'),
                'permissions' => ['mobileapp.index'],
            ]);
        });
    }
}
