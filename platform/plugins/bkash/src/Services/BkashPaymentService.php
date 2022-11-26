<?php

namespace Canopy\Bkash\Services;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Canopy\Bkash\Exceptions\BkashException;
use Illuminate\Http\Request;

class BkashPaymentService extends BkashBaseService
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
        $this->baseUri = $this->getProviderUrl();
        $this->datetime = Carbon::now()->format('YmdHis');
        $this->userName = config('bkash.username');
        $this->callbackUri = route('bkash.payment.callback');
    }

    /**
     * Set Transaction ID and status
     *
     * @param string $orderId
     * @return NagadPaymentService
     */

    public function setInvoiceID(string $orderId): BkashPaymentService
    {
        $this->orderId = 'O'. $orderId .'P'. time();
        $this->internalOrderId = $orderId;
        return $this;
    }

    /**
     * Set Transaction Amount.
     * @param float $amount.
     */
    public function setAmount(float $amount): BkashPaymentService
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * checkout
     *
     * @return BkashPaymentService
     * @throws BkashException
     */
    public function checkout(): BkashPaymentService
    {
        session_start();
        $response = $this->bkashToken();
        $idtoken=$response['id_token'];
        $_SESSION['btoken']= $idtoken;
        if (!empty($_SESSION['btoken'])) {
            $paymentPayload = $this->generatePaymentDataForOrder();
            $this->additionalData['orderId'] = $this->internalOrderId;
            $responseData = $this->completePaymentRequest($paymentPayload);
            if ($responseData['statusCode'] === "0000") {
                $this->redirectUri = $responseData['bkashURL'];
                return $this;
            }
            throw BkashException::couldNotCompleteOrder($responseData);
        }

        throw BkashException::invalidInitResponse($response);
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
     * @return BkashPaymentService
     */
    public function callback(Request $request) : BkashPaymentService
    {
        $this->callbackResponse = $request;
        return $this;
    }

    /**
     * verify
     *
     * @return BkashPaymentService
     */
    public function verify() : BkashPaymentService
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
        return $this->verifiedResponse['statusCode'] === '0000';
    }

    /**
     * getErrors
     *
     * @return array
     */
    public function getErrors() : array
    {
        return [
            'status' => $this->verifiedResponse['statusMessage'],
            'statusCode' => $this->verifiedResponse['statusCode']
        ];
    }

    /**
     * getverifyCallbackResponse
     *
     * @return object
     */
    public function getCallbackResponse() : object
    {
        return $this->callbackResponse;
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