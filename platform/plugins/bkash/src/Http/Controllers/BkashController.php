<?php

namespace Canopy\Bkash\Http\Controllers;
use Throwable;
use OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Canopy\Ecommerce\Models\Currency;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Bkash\Services\BkashPaymentService;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Payment\Services\Traits\PaymentTrait;

class BkashController extends BaseController
{
    use PaymentTrait;
    protected $verified;
    protected $result;
    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function paymentCallback(Request $request, BaseHttpResponse $response)
    {
        $bkashPaymentService = App::make(BkashPaymentService::class);
        if ($request->status == 'success') {
            $this->verified = $bkashPaymentService->callback($request)->verify();
            $this->result = $this->verified->getVerifiedResponse();
        }

        $ins_result = [];

        if($request->status == 'cancel' || $request->status == 'failure' || $this->result['statusCode'] == '2023' || $this->result['statusCode'] == '2029' ){
            $session_value = session()->all();
            // dd($session_value,$session_value['order_id']);
            $currencies = Currency::all()->pluck('exchange_rate', 'title')->toArray();
            $ins_result['currency'] = session()->get('currency');
            $ins_result['order_id'] = $session_value['order_id'] ?? end($session_value)['created_order_id'];
            foreach (session()->get('cart')['cart'] as $value) {
                $ins_result['amount'] = $value->price * $currencies['BDT'];
            }
            $ins_result['paymentID'] = $request->paymentID;
            $this->storeLocalPayment([
                'amount'          => $ins_result['amount'],
                'currency'        => $ins_result['currency'] ?? $ins_result['currency'],
                'charge_id'       => $ins_result['paymentID'],
                'payment_channel' => BKASH_PAYMENT_METHOD_NAME,
                'status'          => PaymentStatusEnum::FAILED,
                'customer_id'     => auth('customer')->check() ? auth('customer')->user()->getAuthIdentifier() : null,
                'response'        => json_encode($this->result),
                'payment_type'    => 'direct',
                'order_id'        => $ins_result['order_id'],
            ]);
            OrderHelper::processOrder($ins_result['order_id'], $ins_result['paymentID']);

            if ($request->status == 'cancel') {
                return $response
                    ->setError()
                    ->setNextUrl(route('public.checkout.failed', session('tracked_start_checkout')))
                    ->setMessage(__('Payment has Failed! Cancel Payment'));
            }elseif($request->status == 'failure') {
                return $response
                ->setError()
                ->setNextUrl(route('public.checkout.failed', session('tracked_start_checkout')))
                ->setMessage(__('Payment has Failed! Wrong OTP'));
            }elseif($this->result['statusCode'] == '2023') {
                return $response
                ->setError()
                ->setNextUrl(route('public.checkout.failed', session('tracked_start_checkout')))
                ->setMessage(__('Payment has Failed! Insufficient Balance'));
            }
            elseif($this->result['statusCode'] == '2029') {
                return $response
                ->setError()
                ->setNextUrl(route('public.checkout.failed', session('tracked_start_checkout')))
                ->setMessage(__('Payment has Failed! Duplicate for all transactions'));
            }
            else {
                return $response
                ->setError()
                ->setNextUrl(route('public.checkout.failed', session('tracked_start_checkout')))
                ->setMessage(__('Payment has Failed! '));
            }
        }

        if ($this->verified->success()) {
            $orderId = explode('P', $this->result['merchantInvoiceNumber']);
            $orderIdStripped = substr($orderId[0], 1);
            // Get Full Response
            $this->storeLocalPayment([
                'amount'          => $this->result['amount'],
                'currency'        => $this->result['currency'] ?? 'BDT',
                'charge_id'       => $this->result['paymentID'],
                'payment_channel' => BKASH_PAYMENT_METHOD_NAME,
                'status'          => PaymentStatusEnum::COMPLETED,
                'customer_id'     => auth('customer')->check() ? auth('customer')->user()->getAuthIdentifier() : null,
                'response'        => json_encode($this->result),
                'payment_type'    => 'direct',
                'order_id'        => $orderIdStripped,
                'trx_id'          => $this->result['trxID'] ?? null
            ]);
            OrderHelper::processOrder($orderIdStripped, $this->result['paymentID']);

            return $response
            ->setNextUrl(route('public.checkout.success', session('tracked_start_checkout')))
            ->setMessage(__('Payment successful'));
        }
    }

}
