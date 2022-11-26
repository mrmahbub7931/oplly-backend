<?php

namespace Canopy\Nagad\Services;

use Canopy\Nagad\Helpers\NagadHelper;

class NagadBaseService
{
    protected $merchantId;
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
     * Generate Payment Data payload to be sent to Nagad API
     *
     * @return array
     */
    protected function generatePaymentData(): array
    {
        return [
            'merchantId' => $this->merchantId,
            'datetime' => $this->datetime,
            'orderId' => $this->orderId,
            'challenge' => NagadHelper::generateRandomString()
        ];
    }

    /**
     * Generates Payment Data array with confirmed order Data
     *
     * currencyCode = 050 = BDT set the currency explicitly as this is for BD region only
     *
     * @return array
     */
    protected function generatePaymentDataForOrder(): array
    {
        return [
            'merchantId' => $this->merchantId,
            'orderId' => $this->orderId,
            'currencyCode' => '050', // 050 = BDT
            'amount' => $this->amount,
            'challenge' => $this->challenge
        ];
    }

    /**
     * Return Nagad API endpoint base URL based on set environment
     *
     * @return string
     */
    public function getProviderUrl(): string
    {
        if (config('nagad.sandbox') === 1) {
            $url = "http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0";
        } else {
            $url = "https://api.mynagad.com";
        }
        return $url . '/api/dfs';
    }

    /**
     * Initialise Payment request with Nagad API
     *
     * @param array $payload
     * @return array
     */
    protected function initPaymentRequest(array $payload): array
    {
        return NagadHelper::httpPostMethod(
            "{$this->baseUri}/check-out/initialize/{$this->merchantId}/{$this->orderId}",
            [
                'accountNumber' => config('nagad.merchantNumber'),
                'dateTime' => $this->datetime,
                'sensitiveData' => NagadHelper::encryptDataWithPublicKey(json_encode($payload)),
                'signature' => NagadHelper::signatureGenerate(json_encode($payload))
            ]
        );
    }

    /**
     * decryptInitialResponse
     *
     * @param mixed $response
     * @return bool
     */
    protected function decodeInitialResponse(array $response): bool
    {
        $decodedResponse = json_decode(
            NagadHelper::decryptDataWithPrivateKey($response['sensitiveData']),
            true
        );

        if (isset($decodedResponse['paymentReferenceId'], $decodedResponse['challenge'])) {
            $this->paymentRefId = $decodedResponse['paymentReferenceId'];
            $this->challenge = $decodedResponse['challenge'];
            return true;
        }
        return false;
    }

    /**
     * completePaymentRequest
     *
     * @param mixed $sensitiveOrderData
     * @return array
     */
    protected function completePaymentRequest(array $sensitiveOrderData): array
    {
        return NagadHelper::httpPostMethod(
            "{$this->baseUri}/check-out/complete/{$this->paymentRefId}",
            [
                'sensitiveData' => NagadHelper::encryptDataWithPublicKey(json_encode($sensitiveOrderData)),
                'signature' => NagadHelper::signatureGenerate(json_encode($sensitiveOrderData)),
                'merchantCallbackURL' => $this->callbackUri,
                'additionalMerchantInfo' => (object)$this->additionalData
            ]
        );
    }

    /**
     * Send request to verify payment status and store it to object property
     *
     * @return void
     */
    public function verifyPayment(): void
    {
        $this->verifiedResponse = NagadHelper::httpGetMethod(
            "{$this->baseUri}/verify/payment/{$this->callbackResponse->payment_ref_id}"
        );
    }
}
