<?php

namespace Canopy\ShurjoPay\Services;

use Canopy\ShurjoPay\Exceptions\ShurjoPayException;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShurjoPayPaymentService extends ShurjoPayBaseService
{

    public function __construct()
    {
        $this->baseUri = $this->getProviderUrl();
        $this->getAuthToken();
        $this->datetime = Carbon::now()->format('YmdHis');
        $this->returnUri = route('shurjopay.payment.callback');
        $this->cancelUri = route('shurjopay.payment.callback');
    }

    /**
     * Set Transaction ID and status
     *
     * @param string $orderId
     * @return ShurjoPayPaymentService
     */

    public function setOrderID(string $orderId): ShurjoPayPaymentService
    {
        $this->orderId = 'O'. $orderId .'P'. time();
        $this->internalOrderId = $orderId;
        return $this;
    }

    /**
     * Set Transaction Amount.
     *
     * @param float $amount .
     * @return ShurjoPayPaymentService
     */
    public function setAmount(float $amount): ShurjoPayPaymentService
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Set Transaction Currency BDT|USD
     *
     * @param string $currency
     * @return ShurjoPayPaymentService
     */
    public function setCurrency(string $currency): ShurjoPayPaymentService
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Set Customer Details.
     *
     * @param array $customerData
     * @return ShurjoPayPaymentService
     */
    public function setCustomer(array $customerData): ShurjoPayPaymentService
    {
        $this->customer = $customerData;
        return $this;
    }

    /**
     * checkout
     *
     * @return ShurjoPayPaymentService
     * @throws ShurjoPayException
     */
    public function checkout(): ShurjoPayPaymentService
    {
        $payload = [
            'prefix' => config('shurjopay.txnprefix'),
            'return_url' => $this->returnUri,
            'cancel_url' => $this->cancelUri,
            'amount' => $this->amount,
            'order_id'  => $this->orderId,
            'currency' => $this->getCurrency(),
            'customer_name' => $this->customer['name'],
            'customer_phone' => $this->customer['phone'],
            'customer_address' => 'online',
            'customer_city' => $this->customer['city'] ?? 'Dhaka'
        ];

        $checkout = $this->getCheckoutUrl($payload);

        if (null === $checkout) {
            throw ShurjoPayException::invalidResponseDuringCheckoutRequest($payload);
        }

        if (!isset($checkout['checkout_url'])) {
            throw ShurjoPayException::missingRequiredFields($checkout);
        }
        $this->redirectUri = $checkout['checkout_url'];
        return $this;
    }

    /**
     * Get redirect url <callback url>
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUri;
    }

    /**
     * redirect
     *
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        return redirect()->away($this->redirectUri);
    }

    /**
     * callback
     *
     * @param  mixed $request
     * @return ShurjoPayPaymentService
     */
    public function callback(Request $request) : ShurjoPayPaymentService
    {
        $this->callbackResponse = $request;
        return $this;
    }

    /**
     * verify
     *
     * @return ShurjoPayPaymentService
     * @throws ShurjoPayException
     */
    public function verify() : ShurjoPayPaymentService
    {
        $this->verifyPayment();
        return $this;
    }

    /**
     * success
     *
     * @return bool
     */
    public function success() : bool
    {
        return $this->verifiedResponse['sp_massage'] === 'Success';
    }

    /**
     * getErrors
     *
     * @return array
     */
    public function getErrors() : array
    {
        return [
            'status' => $this->verifiedResponse['sp_massage'],
            'statusCode' => $this->verifiedResponse['sp_code']
        ];
    }

    /**
     * getVerifiedResponse
     *
     * @return array
     */
    public function getVerifiedResponse() : array
    {
        return $this->verifiedResponse;
    }
}
