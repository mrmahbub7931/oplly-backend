<div class="row promo coupon coupon-section justify-content-center align-content-center" >
    <div class="form-group nice--input mb-0">
        <label for="coupon_code">{{ __('Enter coupon code...') }}</label>
        <input type="text" id="coupon_code" name="coupon_code" class="form-control coupon-code input-md checkout-input" value="{{ old('coupon_code') }}">
        <div class="coupon-error-msg">
            <span class="text-danger"></span>
        </div>
    </div>
    <div class="ml-2 align-content-center d-flex">
        <button class="btn btn-md btn-gray btn-info apply-coupon-code float-right" 
        data-url="{{ route('public.coupon.apply') }}" 
        type="button" 
        style="margin-top: 0;padding: 10px 20px;><i class=">
            <i class="fas fa-gift"></i> {{ __('Apply') }}</button>
    </div>
</div>
