<?php

namespace Canopy\SimpleSlider\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Canopy\SimpleSlider\Models\SimpleSlider;
use Canopy\SimpleSlider\Models\SimpleSliderItem;
use Canopy\SimpleSlider\Repositories\Caches\SimpleSliderItemCacheDecorator;
use Canopy\SimpleSlider\Repositories\Eloquent\SimpleSliderItemRepository;
use Canopy\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;
use Event;
use Illuminate\Support\ServiceProvider;
use Canopy\SimpleSlider\Repositories\Caches\SimpleSliderCacheDecorator;
use Canopy\SimpleSlider\Repositories\Eloquent\SimpleSliderRepository;
use Canopy\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Canopy\Base\Supports\Helper;
use Language;

class SimpleSliderServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(SimpleSliderInterface::class, function () {
            return new SimpleSliderCacheDecorator(new SimpleSliderRepository(new SimpleSlider));
        });

        $this->app->bind(SimpleSliderItemInterface::class, function () {
            return new SimpleSliderItemCacheDecorator(new SimpleSliderItemRepository(new SimpleSliderItem));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/simple-slider')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-simple-slider',
                'priority'    => 100,
                'parent_id'   => null,
                'name'        => 'plugins/simple-slider::simple-slider.menu',
                'icon'        => 'far fa-image',
                'url'         => route('simple-slider.index'),
                'permissions' => ['simple-slider.index'],
            ]);
        });

        $this->app->booted(function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule([SimpleSlider::class]);
            }

            $this->app->register(HookServiceProvider::class);
        });
    }
}
