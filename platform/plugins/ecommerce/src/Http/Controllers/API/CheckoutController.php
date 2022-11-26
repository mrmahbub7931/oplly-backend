<?php

namespace Canopy\Ecommerce\Http\Controllers\API;

use Canopy\Base\Events\UpdatedContentEvent;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Enums\OrderStatusEnum;
use Canopy\Ecommerce\Enums\ShippingMethodEnum;
use Canopy\Ecommerce\Http\Requests\ApplyCouponRequest;
use Canopy\Ecommerce\Http\Requests\CheckoutRequest;
use Canopy\Ecommerce\Http\Requests\CreateOrderRequest;
use Canopy\Ecommerce\Http\Requests\SaveCheckoutInformationRequest;
use Canopy\Ecommerce\Http\Requests\UpdateOrderRequest;
use Canopy\Ecommerce\Http\Resources\RequestResource;
use Canopy\Ecommerce\Repositories\Interfaces\AddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Payment\Repositories\Interfaces\PaymentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TaxInterface;
use Canopy\Ecommerce\Services\HandleApplyCouponService;
use Canopy\Ecommerce\Services\HandleApplyPromotionsService;
use Canopy\Ecommerce\Services\HandleRemoveCouponService;
use Canopy\Ecommerce\Services\HandleShippingFeeService;
use Canopy\Payment\Enums\PaymentMethodEnum;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Payment\Http\Requests\PayPalPaymentCallbackRequest;
use Canopy\Payment\Services\Gateways\BankTransferPaymentService;
use Canopy\Payment\Services\Gateways\CodPaymentService;
use Canopy\Payment\Services\Gateways\PayPalPaymentService;
use Canopy\Payment\Services\Gateways\StripePaymentService;
use Cart;
use EcommerceHelper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use OrderHelper;
use Throwable;

class CheckoutController
{
    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var OccasionInterface
     */
    protected $occasionRepository;

    /**
     * @var OrderProductInterface
     */
    protected $orderProductRepository;

    /**
     * @var OrderAddressInterface
     */
    protected $orderAddressRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * @var ShippingInterface
     */
    protected $shippingRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderHistoryRepository;

    /**
     * @var PaymentInterface
     */
    protected $paymentRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var DiscountInterface
     */
    protected $discountRepository;
    private $talentRepository;

    /**
     * PublicCheckoutController constructor.
     * @param OrderInterface $orderRepository
     * @param OrderProductInterface $orderProductRepository
     * @param OrderAddressInterface $orderAddressRepository
     * @param AddressInterface $addressRepository
     * @param CustomerInterface $customerRepository
     * @param ShippingInterface $shippingRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     * @param ProductInterface $productRepository
     * @param DiscountInterface $discountRepository
     *
     */
    public function __construct(
        OrderInterface $orderRepository,
        OccasionInterface $occasionRepository,
        OrderProductInterface $orderProductRepository,
        OrderAddressInterface $orderAddressRepository,
        AddressInterface $addressRepository,
        CustomerInterface $customerRepository,
        ShippingInterface $shippingRepository,
        OrderHistoryInterface $orderHistoryRepository,
        PaymentInterface $paymentRepository,
        ProductInterface $productRepository,
        DiscountInterface $discountRepository,
        TalentInterface $talentRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->occasionRepository = $occasionRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->shippingRepository = $shippingRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->paymentRepository = $paymentRepository;
        $this->productRepository = $productRepository;
        $this->discountRepository = $discountRepository;
        $this->talentRepository = $talentRepository;
    }

    /**
     * @param string                                               $token
     * @param \Illuminate\Http\Request                             $request
     * @param BaseHttpResponse                                     $response
     * @return BaseHttpResponse
     */
    public function getCheckout(
        $token,
        Request $request,
        BaseHttpResponse $response
    ) {
        $order = $this->orderRepository->getFirstBy(['token' => $token, 'is_finished' => false]);
        if (!$order) {
            return $response->setError()->setCode(404)->setMessage('Request not found');
        }

        $orderData = $this->processOrderData($token, $request);

        return $response->setError()->setCode(404)->setData($orderData)->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse   $response
     * @return BaseHttpResponse
     */
    public function createCheckout(Request $request, BaseHttpResponse $response)
    {

        $talent = $this->talentRepository->getModel()
            ->where('id', $request->input('talent_id'))
            ->with('mainProduct')
            ->first();

        if (!$talent) {
            return $response->setError()->setCode(404)->setMessage('Missing Order Data');
        }

        $request->merge([
            'amount'          => $request->input('amount', 0),
            'currency_id'     => get_application_currency_id(),
            'user_id'         => $request->input('customer_id') ?? 0,
            'token'           => Str::uuid()->toString(),
            //    'shipping_method'      => $request->input('shipping_method', ShippingMethodEnum::DEFAULT),
            //    'shipping_option'      => $request->input('shipping_option'),
            'shipping_amount' => $request->input('shipping_amount'),
            'tax_amount'      => session('tax_amount', 0),
            'sub_total'       => $request->input('amount') - $request->input('shipping_amount') + $request->input('discount_amount'),
            'coupon_code'     => $request->input('coupon_code'),
            'discount_amount' => $request->input('discount_amount'),
            'discount_description' => $request->input('discount_description'),
            'description'     => $request->input('note'),
            'is_confirmed'    => 0,
            'is_finished'     => false,
            'talent_id'       => $request->input('talent_id', 0),
            'status'          => OrderStatusEnum::PENDING,
            'is_speed_service' => $request->input('speed_service', false),
            'from'            => $request->input('from', ''),
            'recepient'       => $request->input('recepient', ''),
            'occasion'        => $request->input('occasion', ''),
            'allow_public'    => $request->input('hide_public', 1),
            'request'         => $request->input('description', ''),
            'target_audience' => $request->input('target_audience', 'single'),
        ]);

        $order = $this->orderRepository->createOrUpdate($request->input());

        if ($order) {
            $this->orderHistoryRepository->createOrUpdate([
                'action' => 'create_order_from_payment_page',
                'description' => trans('plugins/ecommerce::order.create_order_from_payment_page'),
                'order_id' => $order->id,
            ]);

            $this->orderHistoryRepository->createOrUpdate([
                'action' => 'create_order',
                'description' => trans(
                    'plugins/ecommerce::order.new_order',
                    ['order_id' => get_order_code($order->id)]
                ),
                'order_id' => $order->id,
            ]);

            if ($request->input('customer_id')) {
                $customer = $this->customerRepository->findById($request->input('customer_id'));
                $this->orderAddressRepository->create([
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'order_id' => $order->id,
                ]);
            }
            $order->talent()->associate($talent);
            $order->save();

            if ($talent) {
                $product = $this->productRepository->findById($talent->mainProduct->id);
                $data = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'qty' => 1,
                    'weight' => $product->weight,
                    'price' => $product->price,
                    'tax_amount' => EcommerceHelper::isTaxEnabled() ? $product->taxRate / 100 * $product->price : 0,
                    'options' => [],
                ];

                $this->orderProductRepository->create($data);
            }
        }
        $order = $this->orderRepository->getModel()
            ->where('id', $order->id)
            ->with('products', 'talent', 'user')
            ->first();

        return $response
            ->setData(new RequestResource($order))
            ->toApiResponse();
    }

    /**
     * @param int $id
     * @param UpdateOrderRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function updateCheckout($id, UpdateOrderRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->createOrUpdate($request->input(), ['id' => $id]);

        event(new UpdatedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));

        return $response
            ->setData(['message' => 'Order updated successfully'])
            ->toApiResponse();
    }

    /**
     * @param string $token
     * @param CheckoutRequest $request
     * @param BaseHttpResponse $response
     * @return \Canopy\Base\Http\Responses\BaseHttpResponse|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Resources\Json\JsonResource
     * @throws Throwable
     */
    public function processCheckout(
        $token,
        CheckoutRequest $request,
        BaseHttpResponse $response
    ) {
        $this->processOrderData($token, $request);

        $request->merge([
            'status'          => OrderStatusEnum::PENDING,
            'is_finished'     => true,
            'is_speed_service' => $request->input('speed_service', false),
            'from'            => $request->input('from', ''),
            'recepient'       => $request->input('recepient', ''),
            'occasion'        => $request->input('occasion', ''),
            'allow_public'    => $request->input('hide_public', 1),
            'request'         => $request->input('description', ''),
            'target_audience' => $request->input('target_audience', 'single'),
        ]);

        $order = $this->orderRepository->getFirstBy(compact('token'));
        if ($order) {
            $order->fill($request->input());
            $order = $this->orderRepository->createOrUpdate($order);
        } else {
            $order = $this->orderRepository->createOrUpdate($request->input());
        }

        if ($order) {
            $this->orderHistoryRepository->createOrUpdate([
                'action'      => 'create_order_from_api',
                'description' => trans('Order created via API call'),
                'order_id'    => $order->id,
            ]);

            $productItem = $this->productRepository->findById($request->input('product_id'));
            if ($productItem) {
                $price = $request->input('amount');
                if ($request->input('target_audience') == 'corporate') {
                    $price = $request->input('business_price', $price);
                    $data = [
                        'order_id'     => $order->id,
                        'product_id'   => $productItem->id,
                        'product_name' => $productItem->name,
                        'qty'          => 1,
                        'weight'       => 0,
                        'price'        => $price,
                        'tax_amount'   => 0,
                        'options'      => [],
                    ];
                    $this->orderProductRepository->create($data);
                }
            }

            $request->merge([
                'order_id' => $order->id,
            ]);

            $paymentData = [
                'error'     => false,
                'message'   => false,
                'amount'    => $order->amount,
                'currency'  => strtoupper(get_application_currency()->title),
                'type'      => $request->input('payment_method'),
                'charge_id' => null,
            ];

            switch ($request->input('payment_method')) {
                case PaymentMethodEnum::STRIPE:
                    $stripePaymentService = App::make(StripePaymentService::class);
                    $result = $stripePaymentService->execute($request);
                    if ($stripePaymentService->getErrorMessage()) {
                        $paymentData['error'] = true;
                        $paymentData['message'] = $stripePaymentService->getErrorMessage();
                    }
                    $paymentData['charge_id'] = $result;

                    break;

                /*case PaymentMethodEnum::COD:
                    $paymentData['charge_id'] = $codPaymentService->execute($request);
                    break;

                case PaymentMethodEnum::BANK_TRANSFER:
                    $paymentData['charge_id'] = $bankTransferPaymentService->execute($request);
                    break;
                */
                default:
                    $paymentData = apply_filters(PAYMENT_FILTER_AFTER_POST_CHECKOUT, $paymentData, $request);
                    break;
            }

            OrderHelper::processOrder($order->id, $paymentData['charge_id']);

            if ($paymentData['error']) {
                return $response
                    ->setError()
                    ->setData($paymentData)
                    ->toApiResponse();
            }

            return $response
                ->setData($paymentData)
                ->toApiResponse();
        }
        return $response
            ->setError()
            ->setData(['error' => 'order_process_error',
                'message' => __('There is an issue when ordering. Please try again later!')])
            ->toApiResponse();
    }

    /**
     * @param ApplyCouponRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postApplyCoupon(
        ApplyCouponRequest $request,
        BaseHttpResponse $response
    ) {
        $handleApplyCouponService = App::make(HandleApplyCouponService::class);
        $result = $handleApplyCouponService->execute($request);

        if ($result['error']) {
            return $response
                ->setError()
                ->setData($result)
                ->toApiResponse();
        }

        $couponCode = $request->input('coupon_code');

        return $response
            ->setData([
                'message' => 'Discount code applied',
                'code' => $couponCode
            ])->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postRemoveCoupon(
        Request $request,
        BaseHttpResponse $response
    ) {
        $handleRemoveCouponService = App::make(HandleRemoveCouponService::class);
        $result = $handleRemoveCouponService->execute($request->input('token'));

        if ($result['error']) {
            return $response
                ->setError()
                ->setData($result)
                ->toApiResponse();
        }
        return $response
            ->setData($result)
            ->toApiResponse();
    }

    /**
     * @param string  $token
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function processOrderData(string $token, Request $request): ?Model
    {
        $currentUserId = 0;
        if (auth('customer')->check()) {
            $currentUserId = auth('customer')->user()->getAuthIdentifier();
        }

        $price = $request->input('amount');
        // calculate things
        if ($request->input('target_audience') == 'corporate') {
            $price = $request->input('business_price', $price);
        }
        $amount = $price;
        $shippingAmount = 0;
        if ($request->input('delivery_method') == 'speed') {
            $shippingAmount = ($price * 0.40);
        }
        $price += $shippingAmount;
        $talentId = 0;
        $productItem = $this->productRepository->findById($request->input('product_id'));
        if ($productItem) {
            $talentId = $productItem->owner->id;
        }

        $request->merge([
            'amount'          => $price,
            'currency_id'     => get_application_currency_id(),
            'talent_id'       => $talentId,
            'user_id'         => $currentUserId,
            'shipping_method' => $request->input('delivery_method', 'free'),
            'shipping_amount' => $shippingAmount,
            'tax_amount'      => 0,
            'sub_total'       => $amount,
            'coupon_code'     => $request->input('applied_coupon_code', ''),
            'discount_amount' => 0,
            'status'          => OrderStatusEnum::PENDING,
            'is_finished'     => false,
            'is_speed_service' => $request->input('delivery_method', 'free') == 'speed',
            'from'            => $request->input('from', ''),
            'recepient'       => $request->input('recepient', ''),
            'occasion'        => $request->input('occasion', ''),
            'request'         => $request->input('description', ''),
            'target_audience' => $request->input('target_audience', 'single'),
        ]);

        return $this->orderRepository->createOrUpdate($request->input());
        ;
    }
}
