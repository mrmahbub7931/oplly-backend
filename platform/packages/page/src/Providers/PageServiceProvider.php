<?php

namespace Canopy\Page\Providers;

use Canopy\Base\Supports\Helper;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Canopy\Page\Models\Page;
use Canopy\Page\Repositories\Caches\PageCacheDecorator;
use Canopy\Page\Repositories\Eloquent\PageRepository;
use Canopy\Page\Repositories\Interfaces\PageInterface;
use Canopy\Shortcode\View\View;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:50 AM
 */
class PageServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->app->bind(PageInterface::class, function () {
            return new PageCacheDecorator(new PageRepository(new Page));
        });

        $this->setNamespace('packages/page')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-core-page',
                'priority'    => 2,
                'parent_id'   => null,
                'name'        => 'packages/page::pages.menu_name',
                'icon'        => 'fa fa-book',
                'url'         => route('pages.index'),
                'permissions' => ['pages.index'],
            ]);

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink(trans('packages/page::pages.menu_name'), route('pages.index'), 'add-new');
            }
        });

        if (function_exists('shortcode')) {
            view()->composer(['packages/page::themes.page'], function (View $view) {
                $view->withShortcodes();
            });
        }

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(EventServiceProvider::class);
    }
}
