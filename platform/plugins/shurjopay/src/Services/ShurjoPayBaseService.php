<?php

namespace Canopy\ShurjoPay\Services;

use Canopy\ShurjoPay\Exceptions\ShurjoPayException;
use Canopy\ShurjoPay\Helpers\ShurjoHelper;

class ShurjoPayBaseService
{
    protected $merchantId;
    protected $token;
    protected $storeId;
    protected $authHeaders;
    protected $baseUri;
    protected $orderId;
    protected $internalOrderId;
    protected $currency;
    protected $customer;
    protected $amount;
    protected $datetime;
    protected $paymentRefId;
    protected $cancelUri;
    protected $returnUri;
    protected $redirectUri;
    protected $callbackResponse;
    protected $verifiedResponse;

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return ?string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return ?array
     */
    public function getCustomer(): ?array
    {
        return $this->customer;
    }

    /**
     * @return ?array
     */
    public function getTokenHeaders(): ?array
    {
        return $this->token !== null ? [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
        ] : null;
    }

    /**
     * Return API endpoint base URL based on set environment
     *
     * @return string
     */
    public function getProviderUrl(): string
    {
        $prefix = (config('shurjopay.sandbox') === '1') ? 'sandbox.' : 'engine.';
        return 'https://'. $prefix . 'shurjopayment.com';
    }

    /**
     * Request Token Data with API
     *
     * @return void
     * @throws ShurjoPayException
     */
    protected function getAuthToken(): void
    {
        $response = ShurjoHelper::httpPostMethod(
            "{$this->baseUri}/api/get_token",
            [
                'username' => config('shurjopay.username'),
                'password' => config('shurjopay.password'),
            ]
        );

        if (!is_array($response)) {
            throw ShurjoPayException::invalidTokenRequest($response);
        }

        $this->token = $response['token'];
        $this->storeId = $response['store_id'];
    }

    protected function getCheckoutUrl($payload)
    {
        $auth = [
            'token' => $this->getToken(),
            'store_id' => $this->storeId,
            'client_ip' => ShurjoHelper::getClientIp()
        ];
        $finalPayload = array_merge($auth, $payload);
        return ShurjoHelper::httpPostMethod(
            "{$this->baseUri}/api/secret-pay",
            $finalPayload
        );
    }

    /**
     * Send request to verify payment status and store it to object property
     *
     * @return void
     * @throws ShurjoPayException`
     */
    protected function verifyPayment(): void
    {
        if (null === $this->getToken()) {
            throw ShurjoPayException::invalidToken([
                '401' => 'Unauthorized'
            ]);
        }
        $this->verifiedResponse = ShurjoHelper::httpPostMethod(
            "{$this->baseUri}/api/verification",
            [
                'order_id' => $this->callbackResponse->input('order_id')
            ],
            $this->getTokenHeaders()
        );
        if (
            is_array($this->verifiedResponse) &&
            count($this->verifiedResponse) === 1 &&
            is_array($this->verifiedResponse[0])
        ) {
            $this->verifiedResponse = $this->verifiedResponse[0];
        }
    }
}
