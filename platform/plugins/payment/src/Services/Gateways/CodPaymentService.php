<?php

namespace Canopy\Payment\Services\Gateways;

use Canopy\Payment\Enums\PaymentMethodEnum;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Payment\Services\Traits\PaymentTrait;
use Canopy\Support\Services\ProduceServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CodPaymentService implements ProduceServiceInterface
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @return mixed|void
     */
    public function execute(Request $request)
    {
        $chargeId = Str::upper(Str::random(10));

        $this->storeLocalPayment([
            'amount'          => $request->input('amount'),
            'currency'        => $request->input('currency'),
            'charge_id'       => $chargeId,
            'order_id'        => $request->input('order_id'),
            'customer_id'     => auth('customer')->user()->getAuthIdentifier(),
            'payment_channel' => PaymentMethodEnum::COD,
            'status'          => PaymentStatusEnum::PENDING,
        ]);

        return $chargeId;
    }
}
