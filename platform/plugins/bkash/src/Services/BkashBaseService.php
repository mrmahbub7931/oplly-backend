<?php

namespace Canopy\Bkash\Services;

use Canopy\Bkash\Helpers\BkashHelper;

class BkashBaseService{
    protected $userName;
    protected $baseUri;
    protected $orderId;
    protected $internalOrderId;
    protected $amount;
    protected $additionalData;
    protected $datetime;
    protected $paymentRefId;
    protected $challenge;
    protected $callbackUri;
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
        return 'BDT';
    }

    /**
     * Generates Payment Data array with confirmed order Data
     *
     * currency BDT set the currency explicitly as this is for BD region only
     *
     * @return array
     */
    protected function generatePaymentDataForOrder(): array
    {
        return [
            'mode' => '0011',
            'amount' => $this->amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'payerReference' => ' ',
            'merchantInvoiceNumber' => $this->orderId,
            'callbackURL' => $this->callbackUri
        ];
    }

    /**
     * Return Bkash API endpoint base URL based on set environment
     *
     * @return string
     */
    public function getProviderUrl(): string
    {
        if (config('bkash.sandbox') == 1) {
            $url = "https://tokenized.sandbox.bka.sh/v1.2.0-beta";
        } else {
            $url = "https://tokenized.pay.bka.sh/v1.2.0-beta";
        }
        return $url;
    }

    /**
     * Send Request to get Token with Bkash API
     *
     * @return array
     */
    protected function bkashToken(): array
    {
        return BkashHelper::tokenPostMethod(
            "{$this->baseUri}/tokenized/checkout/token/grant",
            [
                'app_key'=> config('bkash.appKey'),
                'app_secret'=> config('bkash.appSecret'),
            ]
        );
    }


    /**
     * completePaymentRequest
     *
     * @param mixed $payloadData
     * @return array
     */
    protected function completePaymentRequest(array $payloadData): array
    {
        return BkashHelper::createPostMethod(
            "{$this->baseUri}/tokenized/checkout/create",$payloadData
        );
    }

    /**
     * Send request to verify payment status and store it to object property
     *
     * @return void
     */
    public function verifyPayment(): void
    {
        $this->verifiedResponse = BkashHelper::executePostMethod(
            "{$this->baseUri}/tokenized/checkout/execute/",
            [
                'paymentID' => $this->callbackResponse->paymentID
            ]
        );
    }
}