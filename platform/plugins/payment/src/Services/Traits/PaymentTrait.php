<?php

namespace Canopy\Payment\Services\Traits;

use Auth;
use Canopy\Payment\Enums\PaymentMethodEnum;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Payment\Models\Payment;
use Canopy\Payment\Repositories\Interfaces\PaymentInterface;
use Illuminate\Support\Arr;

trait PaymentTrait
{

    /**
     * Store payment on local
     *
     * @param array $args
     * @return mixed
     */
    public function storeLocalPayment(array $args = [])
    {
        $data = array_merge([
            'user_id' => Auth::check() ? Auth::user()->getAuthIdentifier() : 0,
        ], $args);

        $paymentChannel = Arr::get($data, 'payment_channel', PaymentMethodEnum::STRIPE);
        
        return app(PaymentInterface::class)->create([
            'account_id'      => Arr::get($data, 'account_id'),
            'amount'          => $data['amount'],
            'currency'        => $data['currency'],
            'charge_id'       => $data['charge_id'],
            'customer_id'     => $data['customer_id'],
            'order_id'        => $data['order_id'],
            'response'        => $data['response'] ?? null,
            'payment_channel' => $paymentChannel,
            'status'          => Arr::get($data, 'status', PaymentStatusEnum::PENDING),
            'trx_id'          => $data['trx_id'] ?? null
        ]);
    }
}
