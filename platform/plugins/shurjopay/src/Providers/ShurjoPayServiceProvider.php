<?php

namespace Canopy\ShurjoPay\Providers;

use Canopy\Base\Supports\Helper;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\ServiceProvider;

class ShurjoPayServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @throws FileNotFoundException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        if (is_plugin_active('payment')) {
            $this->setNamespace('plugins/shurjopay')
                ->loadRoutes(['web'])
                ->loadAndPublishViews()
                ->publishAssets();

            $this->app->register(HookServiceProvider::class);

            $config = $this->app->make('config');

            $config->set([
                'shurjopay.username'  => get_payment_setting('username', SHURJO_PAYMENT_METHOD_NAME),
                'shurjopay.password'  => get_payment_setting('password', SHURJO_PAYMENT_METHOD_NAME),
                'shurjopay.txnprefix' => get_payment_setting('txnprefix', SHURJO_PAYMENT_METHOD_NAME),
                'shurjopay.sandbox' => get_payment_setting('sandbox', SHURJO_PAYMENT_METHOD_NAME)
            ]);
        }
    }
}
