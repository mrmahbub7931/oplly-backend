<?php

namespace Canopy\Bkash\Providers;

use Canopy\Ecommerce\Models\Currency;
use Canopy\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Canopy\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Canopy\Bkash\Services\BkashPaymentService;
use Canopy\Payment\Enums\PaymentMethodEnum;
use Html;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
// use Code4mk\Nagad\Nagad;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerBkashMethod'], 18, 2);
        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithBkash'], 18, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 97, 1);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['bkash'] = BKASH_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == BKASH_PAYMENT_METHOD_NAME) {
                $value = 'Bkash';
            }

            return $value;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == BKASH_PAYMENT_METHOD_NAME) {
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
        return $settings . view('plugins/bkash::settings')->render();
    }

    /**
     * @param string $html
     * @param array $data
     * @return string
     * @throws Throwable
     */
    public function registerBkashMethod(string $html, array $data): string
    {
        return $html . view('plugins/bkash::methods', $data)->render();
    }

    /**
     * @param array $data
     * @param Request $request
     * @return array|void
     * @throws BindingResolutionException
     */
    public function checkoutWithBkash(array $data, Request $request)
    {
        if ($request->input('payment_method') === BKASH_PAYMENT_METHOD_NAME) {
            $currencies = Currency::all()->pluck('exchange_rate', 'title')->toArray();
            // ensure that amount is converted to BDT
            $amount = $request->input('amount') * $currencies['BDT'];
            $bkash = App::make(BkashPaymentService::class);
            $redirectUrl = $bkash->setInvoiceID($request->input('order_id'))
                    ->setAmount($amount)
                    ->checkout()
                    ->getRedirectUrl();
            header('Location: ' . $redirectUrl);
            exit;
        }
        return $data;

    }
}
