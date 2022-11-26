<?php
namespace Canopy\Bkash\Providers;

use Canopy\Base\Supports\Helper;
use Illuminate\Support\ServiceProvider;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class BkashServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        if (is_plugin_active('payment')) {
            $this->setNamespace('plugins/bkash')
                ->loadRoutes(['web'])
                ->loadAndPublishViews()
                ->publishAssets();

            $this->app->register(HookServiceProvider::class);

            $config = $this->app->make('config');

            $config->set([
                'bkash.appKey' => get_payment_setting('app_key', BKASH_PAYMENT_METHOD_NAME),
                'bkash.appSecret' => get_payment_setting('app_secret', BKASH_PAYMENT_METHOD_NAME),
                'bkash.username' => get_payment_setting('username', BKASH_PAYMENT_METHOD_NAME),
                'bkash.password' => get_payment_setting('password', BKASH_PAYMENT_METHOD_NAME),
                'bkash.sandbox' => get_payment_setting('sandbox', BKASH_PAYMENT_METHOD_NAME)
            ]);
        }
    }
}
