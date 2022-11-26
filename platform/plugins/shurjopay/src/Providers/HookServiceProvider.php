<?php

namespace Canopy\ShurjoPay\Providers;

use Canopy\Ecommerce\Models\Currency;
use Canopy\Payment\Enums\PaymentMethodEnum;
use Canopy\ShurjoPay\Services\ShurjoPayPaymentService;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerShurjoMethod'], 16, 2);
        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithShurjo'], 16, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 97, 1);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['shurjopay'] = SHURJO_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == SHURJO_PAYMENT_METHOD_NAME) {
                $value = 'ShurjoPay';
            }

            return $value;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == SHURJO_PAYMENT_METHOD_NAME) {
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
    public function addPaymentSettings(string $settings): string
    {
        return $settings . view('plugins/shurjopay::settings')->render();
    }

    /**
     * @param string $html
     * @param array $data
     * @return string
     * @throws Throwable
     */
    public function registerShurjoMethod($html, array $data): string
    {
        return $html . view('plugins/shurjopay::methods', $data)->render();
    }

    /**
     * @param array $data
     * @param Request $request
     * @return array|void
     */
    public function checkoutWithShurjo(array $data, Request $request)
    {
        if ($request->input('payment_method') === SHURJO_PAYMENT_METHOD_NAME) {
            $currencies = Currency::all()->pluck('exchange_rate', 'title')->toArray();
            // ensure that amount is converted to BDT
            $amount = $request->input('amount') * $currencies['BDT'];
            $nagad = App::make(ShurjoPayPaymentService::class);
            $customer = auth('customer')->user();
            $redirectUrl = $nagad->setOrderID($request->input('order_id'))
                ->setAmount($amount)
                ->setCurrency($request->input('currency', 'BDT'))
                ->setCustomer([
                    'name' => $customer->name ?? '',
                    'phone' => $customer->phone ?? '03301139791',
                    'city' => $request->input('customer_city', 'Dhaka'),
                ])
                ->checkout()
                ->getRedirectUrl();
            header('Location: ' . $redirectUrl);
            exit;
        }
        return $data;
    }
}
