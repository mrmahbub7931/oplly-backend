<?php

namespace Canopy\Nagad\Providers;

use Canopy\Base\Supports\Helper;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Canopy\Nagad\Facades\Nagad;
use Canopy\Nagad\Services\NagadPaymentService;

class NagadServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @return void
     */
    public function register(): void
    {
        Helper::autoload(__DIR__ . '/../../helpers');
        $this->app->bind('nagad', function () {
            return new NagadPaymentService;
        });
    }

    /**
     * @throws FileNotFoundException
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        if (is_plugin_active('payment')) {
            $this->setNamespace('plugins/nagad')
                ->loadRoutes(['web'])
                ->loadAndPublishViews()
                ->publishAssets();

            $this->app->register(HookServiceProvider::class);

            $config = $this->app->make('config');

            $config->set([
                'nagad.publicKey' => get_payment_setting('public', NAGAD_PAYMENT_METHOD_NAME),
                'nagad.privateKey' => get_payment_setting('secret', NAGAD_PAYMENT_METHOD_NAME),
                'nagad.merchantEmail' => get_payment_setting('merchant_email', NAGAD_PAYMENT_METHOD_NAME),
                'nagad.merchantId' => get_payment_setting('merchant_id', NAGAD_PAYMENT_METHOD_NAME),
                'nagad.merchantNumber' => get_payment_setting('merchant_number', NAGAD_PAYMENT_METHOD_NAME),
                'nagad.sandbox' => get_payment_setting('sandbox', NAGAD_PAYMENT_METHOD_NAME)
            ]);

            AliasLoader::getInstance()->alias('Nagad', Nagad::class);
        }
    }
}
