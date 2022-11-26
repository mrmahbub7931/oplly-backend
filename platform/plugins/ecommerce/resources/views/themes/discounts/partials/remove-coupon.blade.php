<div class="row promo justify-content-center align-content-center">
    <div class="">
        <div class="alert alert-success coupon-text">
           {{ __('Coupon code: :code', ['code' => session('applied_coupon_code')]) }}
        </div>
    </div>
    <div class="ml-1">
        <button class="btn btn-md btn-gray btn-warning remove-coupon-code" data-url="{{ route('public.coupon.remove') }}" type="button" style="padding: 12px;"><i class="fas fa-trash"></i> {{ __('Remove') }}</button>
    </div>
    <div class="clearfix"></div>
    
    @if (session('applied_coupon_code'))

    <div class="col-md-12">
        <div class="text-center">
            <span class="text-success">
                {{ __('Congratulations! You got') }} {{ get_coupon(session('applied_coupon_code'))->value }}{{ __('% discount.') }}
            </span>
        </div>
    </div>
        
    @endif

    <div class="col-md-12">
        <div class="coupon-error-msg">
            <span class="text-danger"></span>
        </div>
    </div>
</div>
