<?php

namespace Canopy\Nagad\Providers;

use Canopy\Ecommerce\Models\Currency;
use Canopy\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Canopy\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Canopy\Nagad\Services\NagadPaymentService;
use Canopy\Payment\Enums\PaymentMethodEnum;
use Html;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Code4mk\Nagad\Nagad;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerNagadMethod'], 18, 2);
        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithNagad'], 18, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 97, 1);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['nagad'] = NAGAD_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == NAGAD_PAYMENT_METHOD_NAME) {
                $value = 'Nagad';
            }

            return $value;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == NAGAD_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 21, 2);
    }

    /**
     * @param string $settings
     * @return string
     * @throws Throwable
     */
    public function addPaymentSettings($settings)
    {
        return $settings . view('plugins/nagad::settings')->render();
    }

    /**
     * @param string $html
     * @param array $data
     * @return string
     * @throws Throwable
     */
    public function registerNagadMethod(string $html, array $data): string
    {
        return $html . view('plugins/nagad::methods', $data)->render();
    }

    /**
     * @param array $data
     * @param Request $request
     * @return array|void
     * @throws BindingResolutionException
     */
    public function checkoutWithNagad(array $data, Request $request)
    {
        if ($request->input('payment_method') === NAGAD_PAYMENT_METHOD_NAME) {
            $currencies = Currency::all()->pluck('exchange_rate', 'title')->toArray();
            // ensure that amount is converted to BDT
            $amount = $request->input('amount') * $currencies['BDT'];
            $nagad = App::make(NagadPaymentService::class);
            $redirectUrl = $nagad->setOrderID($request->input('order_id'))
                ->setAmount($amount)
                ->checkout()
                ->getRedirectUrl();
            header('Location: ' . $redirectUrl);
            exit;
        }
        return $data;
    }
}
