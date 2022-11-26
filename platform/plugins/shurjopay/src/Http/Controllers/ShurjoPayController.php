<?php

namespace Canopy\ShurjoPay\Http\Controllers;

use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Payment\Services\Traits\PaymentTrait;
use Canopy\ShurjoPay\Services\ShurjoPayPaymentService;
use Illuminate\Support\Facades\App;
use OrderHelper;
use Illuminate\Http\Request;
use Throwable;

class ShurjoPayController extends BaseController
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getPaymentStatus(Request $request, BaseHttpResponse $response)
    {
        $paymentService = App::make(ShurjoPayPaymentService::class);
        $verified = $paymentService->callback($request)->verify();
        $result = $verified->getVerifiedResponse();

        if (isset($result['customer_order_id'])) {
            $orderId = explode('P', $result['customer_order_id']);
            $orderIdStripped = substr($orderId[0], 1);
        }

        $this->storeLocalPayment([
            'amount'          => $result['amount'],
            'currency'        => $result['currency'],
            'charge_id'       => $request->input('order_id'),
            'payment_channel' => SHURJO_PAYMENT_METHOD_NAME,
            'status'          => $verified->success() ? PaymentStatusEnum::COMPLETED : PaymentStatusEnum::FAILED,
            'customer_id'     => auth('customer')->check() ? auth('customer')->user()->getAuthIdentifier() : null,
            'payment_type'    => 'direct',
            'response'        => json_encode($result),
            'order_id'        => $orderIdStripped,
        ]);

        OrderHelper::processOrder($orderIdStripped, $request->input('order_id'));

        if ($verified->success()) {
            return $response
                ->setNextUrl(route('public.checkout.success', session('tracked_start_checkout')))
                ->setMessage(__('Payment successful'));
        }

        $result = $verified->getErrors();
        return $response
            ->setError()
            ->setNextUrl(route('public.checkout.failed', session('tracked_start_checkout')))
            ->setMessage(__('Payment has Failed'));

    }
}
