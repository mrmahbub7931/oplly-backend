<?php

namespace Canopy\Ecommerce\Services;

use Canopy\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Illuminate\Support\Arr;
use OrderHelper;

class HandleRemoveCouponService
{
    /**
     * @var DiscountInterface
     */
    protected $discountRepository;

    /**
     * PublicController constructor.
     * @param DiscountInterface $discountRepository
     */
    public function __construct(DiscountInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param string $token
     * @return array
     */
    public function execute($token)
    {
        if (!session()->has('applied_coupon_code')) {
            return [
                'error'   => true,
                'message' => trans('plugins/ecommerce::discount.not_used'),
            ];
        }

        $couponCode = session('applied_coupon_code');

        $discount = $this->discountRepository
            ->getModel()
            ->where('code', $couponCode)
            ->where('type', 'coupon')
            ->first();

        if (!$token) {
            $token = OrderHelper::getOrderSessionToken();
        }

        $sessionData = OrderHelper::getOrderSessionData($token);

        if ($discount && $discount->type_option === 'shipping') {
            Arr::set($sessionData, 'is_free_ship', false);
        }

        Arr::set($sessionData, 'coupon_discount_amount', 0);
        OrderHelper::setOrderSessionData($token, $sessionData);

        session()->forget('applied_coupon_code');

        return [
            'error' => false,
        ];
    }
}
