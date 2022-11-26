<?php

namespace Canopy\Nagad\Services;

use Canopy\Nagad\Exceptions\NagadException;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NagadPaymentService extends NagadBaseService
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
        $this->baseUri = $this->getProviderUrl();
        $this->datetime = Carbon::now()->format('YmdHis');
        $this->merchantId = config('nagad.merchantId');
        $this->callbackUri = route('nagad.payment.callback');
    }

    /**
     * Set Transaction ID and status
     *
     * @param string $orderId
     * @return NagadPaymentService
     */

    public function setOrderID(string $orderId): NagadPaymentService
    {
        $this->orderId = 'O'. $orderId .'P'. time();
        $this->internalOrderId = $orderId;
        return $this;
    }

    /**
     * Set Transaction Amount.
     * @param float $amount.
     */
    public function setAmount(float $amount): NagadPaymentService
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * checkout
     *
     * @return NagadPaymentService
     * @throws NagadException
     */
    public function checkout(): NagadPaymentService
    {
        $response = $this->initPaymentRequest($this->generatePaymentData());

        if (!empty($response['sensitiveData']) && !empty($response['signature'])) {
            if ($this->decodeInitialResponse($response)) {
                $paymentPayload = $this->generatePaymentDataForOrder();
                $this->additionalData['orderId'] = $this->internalOrderId;

                $responseData = $this->completePaymentRequest($paymentPayload);

                if ($responseData['status'] === "Success") {
                    $this->redirectUri = $responseData['callBackUrl'];
                    return $this;
                }

                throw NagadException::couldNotCompleteOrder($responseData);
            }

            throw NagadException::couldNotDecryptInitResponse($response);
        }

        throw NagadException::invalidInitResponse($response);
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
     * @return NagadPaymentService
     */
    public function callback(Request $request) : NagadPaymentService
    {
        $this->callbackResponse = $request;
        return $this;
    }

    /**
     * verify
     *
     * @return NagadPaymentService
     */
    public function verify() : NagadPaymentService
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
        return $this->verifiedResponse['status'] === 'Success';
    }

    /**
     * getErrors
     *
     * @return array
     */
    public function getErrors() : array
    {
        return [
            'status' => $this->verifiedResponse['status'],
            'statusCode' => $this->verifiedResponse['statusCode']
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

    /**
     * getAdditionalData
     *
     * @param  mixed $object
     * @return mixed
     */
    public function getAdditionalData($object = true)
    {
        return json_decode($this->verifiedResponse['additionalMerchantInfo'], $object);
    }
}
