<?php

namespace Canopy\Nagad\Http\Controllers;

use Canopy\Nagad\Services\NagadPaymentService;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Payment\Services\Traits\PaymentTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use OrderHelper;
use Illuminate\Http\Request;
use Throwable;

class NagadController extends BaseController
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function paymentCallback(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $nagadPaymentService = App::make(NagadPaymentService::class);
        $verified = $nagadPaymentService->callback($request)->verify();
        Log::info($verified->getVerifiedResponse());

        $result = $verified->getVerifiedResponse();
        // Get Additional Data
        $orderId = $verified->getAdditionalData();

        if ($orderId === null) {
            $orderId = explode('P', $result['orderId']);
            $orderIdStripped = substr($orderId[0], 1);
        }

        // Get Full Response
        $this->storeLocalPayment([
            'amount'          => $result['amount'],
            'currency'        => $result['currency'] ?? 'BDT',
            'charge_id'       => $result['issuerPaymentRefNo'] ?? $result['paymentRefId'],
            'payment_channel' => NAGAD_PAYMENT_METHOD_NAME,
            'status'          => $verified->success() ? PaymentStatusEnum::COMPLETED : PaymentStatusEnum::FAILED,
            'customer_id'     => auth('customer')->check() ? auth('customer')->user()->getAuthIdentifier() : null,
            'response'        => json_encode($result),
            'payment_type'    => 'direct',
            'order_id'        => $orderId['orderId'] ?? $orderIdStripped,
        ]);
        OrderHelper::processOrder($orderId['orderId'] ?? $orderIdStripped, $result['issuerPaymentRefNo'] ?? $result['paymentRefId']);

        if ($verified->success()) {
            return $response
                ->setNextUrl(route('public.checkout.success', session('tracked_start_checkout')))
                ->setMessage(__('Payment successful'));
        } else {
            $result = $verified->getErrors();
            return $response
                ->setError()
                ->setNextUrl(route('public.checkout.failed', session('tracked_start_checkout')))
                ->setMessage(__('Payment has Failed'));
        }
    }
}
