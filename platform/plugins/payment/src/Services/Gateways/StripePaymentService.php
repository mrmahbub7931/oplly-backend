<?php

namespace Canopy\Payment\Services\Gateways;

use Canopy\Payment\Enums\PaymentMethodEnum;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Payment\Services\Abstracts\StripePaymentAbstract;
use Canopy\Payment\Services\Traits\PaymentTrait;
use Canopy\Payment\Supports\StripeHelper;
use Exception;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Auth;

class StripePaymentService extends StripePaymentAbstract
{
    use PaymentTrait;

    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     * @throws ApiErrorException
     */
    public function makePayment(Request $request)
    {

        $this->amount = format_price($request->input('amount'), get_single_currency('USD')->id ?? 1, true, false); // TODO::find usd dynamically
        
        // $this->currency_config = $request->input('currency', config('plugins.payment.payment.currency'));
        $this->currency_config = "USD";
        $this->currency = strtoupper($this->currency_config);
        $description = $request->input('description');

        Stripe::setApiKey(setting('payment_stripe_secret'));
        Stripe::setClientId(setting('payment_stripe_client_id'));

        $amount = $this->amount;

        $multiplier = StripeHelper::getStripeCurrencyMultiplier($this->currency);

        if ($multiplier > 1) {
            $amount = (int) ($amount * $multiplier);
        }

        $charge = Charge::create([
            'amount'      => $amount,
            'currency'    => $this->currency,
            'source'      => $this->token,
            'description' => $description,
            "metadata" => array(
                                "customer_id" => auth('customer')->user()->id, 
                                "name" => auth('customer')->user()->name
                            )
        ]);

        $this->chargeId = $charge['id'];

        return $this->chargeId;
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param string $chargeId
     * @param Request $request
     *
     * @return mixed
     */
    public function afterMakePayment($chargeId, Request $request)
    {
        try {
            $payment = $this->getPaymentDetails($chargeId);
            if ($payment && ($payment->paid || $payment->status == 'succeeded')) {
                $paymentStatus = PaymentStatusEnum::COMPLETED;
            } else {
                $paymentStatus = PaymentStatusEnum::FAILED;
            }
        } catch (Exception $exception) {
            $paymentStatus = PaymentStatusEnum::FAILED;
        }

        $this->storeLocalPayment([
            'amount'          => $this->amount,
            'currency'        => $this->currency,
            'charge_id'       => $chargeId,
            'order_id'        => $request->input('order_id'),
            "customer_id"     => auth('customer')->user()->id,
            'payment_channel' => PaymentMethodEnum::STRIPE,
            'status'          => $paymentStatus,
        ]);

        return true;
    }
}
