<?php

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

if (!function_exists('render_review_form')) {
    /**
     * @param int $productId
     * @return string
     * @throws FileNotFoundException
     * @throws Throwable
     */
    function render_review_form($productId)
    {
        Theme::asset()->container('footer')->usePath(false)->add(
            'star-rating-js',
            asset('vendor/core/plugins/ecommerce/libraries/star-rating/star-rating.min.js')
        );
        Theme::asset()->container('footer')->usePath(false)->add(
            'review-js',
            asset('vendor/core/plugins/ecommerce/js/review.js')
        );
        Theme::asset()->usePath(false)->add(
            'star-rating-css',
            asset('vendor/core/plugins/ecommerce/libraries/star-rating/star-rating.min.css')
        );
        Theme::asset()->usePath(false)->add('review-css', asset('vendor/core/plugins/ecommerce/css/review.css'));

        $reviews = app(ReviewInterface::class)->allBy([
            'status'     => BaseStatusEnum::PUBLISHED,
            'product_id' => $productId,
        ]);

        return view('plugins/ecommerce::themes.review-form', compact('productId', 'reviews'))->render();
    }
}

if (!function_exists('check_if_reviewed_product')) {
    /**
     * @param int $productId
     * @param int $customerId
     * @return bool
     */
    function check_if_reviewed_product($productId, $customerId = null)
    {
        if ($customerId == null && auth('customer')->check()) {
            $customerId = auth('customer')->user()->getAuthIdentifier();
        }

        $existed = app(ReviewInterface::class)->count([
            'customer_id' => $customerId,
            'product_id'  => $productId,
        ]);

        return $existed > 0;
    }
}

if (!function_exists('get_customer_reviewed_value')) {
    /**
     * @param int $productId
     * @param int $customerId
     * @return int
     */
    function get_customer_reviewed_value($productId, $customerId = null)
    {
        if ($customerId == null && auth('customer')->check()) {
            $customerId = auth('customer')->user()->getAuthIdentifier();
        }

        $review = app(ReviewInterface::class)->getFirstBy([
            'customer_id' => $customerId,
            'product_id'  => $productId,
        ]);

        if (!empty($review)) {
            return $review->star;
        }

        return 0;
    }
}

if (!function_exists('get_count_reviewed_of_product')) {
    /**
     * @param int $productId
     * @return mixed
     */
    function get_count_reviewed_of_product($productId)
    {
        return app(ReviewInterface::class)->count([
            'product_id' => $productId,
            'status'     => BaseStatusEnum::PUBLISHED,
        ]);
    }
}

if (!function_exists('get_average_star_of_product')) {
    /**
     * @param int $productId
     * @return mixed
     */
    function get_average_star_of_product($productId)
    {
        $avg = (float)app(ReviewInterface::class)->getModel()
            ->where('product_id', $productId)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->avg('star');

        return number_format($avg, 2);
    }
}
 
if (!function_exists('check_mutiple_review_feature')) { 
    /** 
     * @param int $productId 
     * @return mixed 
     */ 
    /** 
     * It checks if the multiple review feature is enabled or not. 
     *  
     * @return A boolean value. 
     */ 
    function check_mutiple_review_feature() 
    { 
        /* Checking the value of the `multiple_review` key in the `plugins.ecommerce.review` config
        file. */ 
        $check_option = config('plugins.ecommerce.review.multiple_review'); 
 
        if ($check_option == true) { 
            return true; 
        }else{ 
            return false; 
        } 
    } 
} 
