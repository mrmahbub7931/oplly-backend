@extends('plugins/ecommerce::orders.master')
@section('title')
{{ __('Checkout') }}
@stop
@section('content')
<link rel="stylesheet" href="{{ asset('vendor/core/plugins/payment/libraries/card/card.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/core/plugins/payment/css/payment.css') }}?v=1.0.2">

@if (Cart::instance('cart')->count() > 0)
{!! Form::open(['route' => ['public.checkout.process', $token], 'class' => 'checkout-form payment-checkout-form', 'id' => 'checkout-form']) !!}
<input type="hidden" name="checkout-token" id="checkout-token" value="{{ $token }}">
@php
$productIds = Cart::instance('cart')
->content()
->pluck('id')
->toArray();
if ($productIds) {
$products = get_products([
'condition' => [['ec_products.id', 'IN', $productIds]],
]);
}
@endphp

<div class="row">
  <div class="col-12 col-lg-8 mx-auto">

    {{-- @if (theme_option('logo'))
        <div class="checkout-logo">
            <a href="{{ url('/') }}" title="{{ theme_option('site_title') }}">
    <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" class="img-fluid" width="150" alt="{{ theme_option('site_title') }}" />
    </a>
  </div>
  <hr />
  @endif --}}

  <div class="form-checkout">@if (isset($products) && $products)
    @php
    $cartContent = Cart::instance('cart')
    ->content()
    ->first();
    // dd($cartContent);
    $product = $products->where('id', $cartContent->id)->first();
    @endphp
    <div class="talent--pic">
      <img src="{{ RvMedia::getImageUrl($product->owner->photo, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}">
    </div>
    <h1>Send your request


      @if (!empty($product))
      to {{ $product->name }}
      @endif

    </h1>@endif
    <form action="{{ route('payments.checkout') }}" method="post">
      @csrf

      <div class="form-group mb-3">

        <div class="text-center mb-4">
          <h5 class="checkout-payment-title">{{ __('This is for') }}</h5>
          <p class="text-muted">Please choose</p>
        </div>
      </div>

      <div class="form-group  @if ($errors->has('target_audience')) has-error @endif">
        <div class="btn-group pills--selector" role="group" aria-label="target_audience" id="target_audience_select">
          <input type="radio" class="btn-check" name="target_audience" id="target_audience_him" autocomplete="off" checked value="single">
          <label class="btn btn-outline-primary" for="target_audience_him">
            <span>
              <i class="fas fa-user"></i>
            </span>
            Someone else</label>

          <input type="radio" class="btn-check" name="target_audience" id="target_audience_self" autocomplete="off" value="self">
          <label class="btn btn-outline-primary" for="target_audience_self">
            <span>
              <i class="fas fa-star"></i>
            </span>
            For Me</label>
          <input type="hidden" id="default_price" name="price" value="{{ $product->owner->price }}">
          {{-- <input type="radio" class="btn-check" name="target_audience" id="target_audience_them" autocomplete="off"
                  value="them">
                <label class="btn btn-outline-primary" for="target_audience_them">Them</label> --}}
          @if ($product->owner and $product->owner->allow_business)

          <input type="radio" class="btn-check" name="target_audience" id="target_audience_corporate" autocomplete="off" value="corporate">
          <label class="btn btn-outline-primary" for="target_audience_corporate">
            <span>
              <i class="fas fa-briefcase"></i>
            </span>
            Business <b>{{ format_price($product->owner->business_price) }}</b></label>
          <input type="hidden" id="business_price" name="business_price" value="{{ $product->owner->business_price }}">
          @endif
        </div>
      </div>
      <input type="hidden" name="service_fee" value="0.05">

      <div class="form-group mb-3 nice--input @if ($errors->has('recepient')) has-error @endif">
        <label for="recepient">{{ __('What\'s their name?') }}</label>
        <input type="text" name="recepient" id="recepient" class="form-control address-control-item checkout-input" value="{{ old('recepient"', Arr::get($sessionCheckoutData, 'recepient"')) }}">
        {!! Form::error('recepient', $errors) !!}
      </div>

      <div class="form-group mb-3 hide_on_self nice--input  @if ($errors->has('from')) has-error @endif">
        <label for="from">{{ __('Who is it from?') }}</label>
        <input type="text" name="from" id="from" class="form-control address-control-item checkout-input" value="{{ old('from', Arr::get($sessionCheckoutData, 'from')) }}">
        {!! Form::error('from', $errors) !!}
      </div>

      <div class="form-group mb-3 @if ($errors->has('description')) has-error @endif">
        <div class="text-center mb-4 mt-4">
          <h5 class="checkout-payment-title">{{ __('Choose an occasion') }}</h5>
          <p class="text-muted">Please choose</p>
        </div>
      </div>

      <div class="form-group  @if ($errors->has('address.email')) has-error @endif">
        <ul class="list-group list_occasion--selector">
          @foreach ($occasions as $occasion)
          <li class="@if($occasion->show_standard) show--default @endif @if($occasion->show_business) show--business @endif">
            <label class="btn" for="occasion_{{ $occasion->id }}">
              <input type="radio" class="btn-check" name="occasion_id" id="occasion_{{ $occasion->id }}" autocomplete="off" value="{{ $occasion->id }}">
              <img src="{{ RvMedia::getImageUrl($occasion->image, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $occasion->name }}" />
              <span>{{ $occasion->name }}</span></label>
          </li>
          @endforeach
        </ul>
      </div>

      <br>
      <input type="hidden" name="shipping_option" value="{{ old('shipping_option', $defaultShippingOption) }}">
      <br>

      <div class="form-group">
        <div class="text-center mb-4">
          <h5 class="checkout-payment-title">{{ __('Provide Instructions') }}</h5>
          <p class="text-muted">Provide your instructions on what you want in the message</p>
        </div>
      </div>
      <div class="form-group mb-3 nice--input text--countable @if ($errors->has('description')) has-error @endif" data-limit="600">
        <label for="description">Instructions</label>
        <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
        {!! Form::error('description', $errors) !!}
      </div>

      {{--<div class="form-group mb-3">
              <div class="chek-form">
                <div class="custome-checkbox">
                  <input class="form-check-input" type="checkbox" checked name="make_public" id="make_public-me" value="1">
                  <label class="form-check-label text-muted"
                    for="make_public-me"><span>{{ __('Show this video on public profile') }}</span></label>
  </div>
</div>
</div>--}}

<div class="form-group mb-3">
  <div class="chek-form">
    <div class="custome-checkbox">
      <input class="form-check-input" type="checkbox" name="hide_public" id="make_public-me" value="0">
      <label class="form-check-label text-muted" for="make_public-me"><span>Hide this video from @if (!empty($product))
          {{ $product->name }}'s
          @endif profile</span></label>
    </div>
  </div>
</div>

<br>

<div class="mb-4">
  <div class="text-center mb-4">
    <h5 class="checkout-payment-title">{{ __('Payment & Booking') }}</h5>
    <p class="text-muted">Select the payment method and confirm your request</p>
  </div>
  <input type="hidden" name="amount" value="{{ Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount + $shippingAmount }}">
  <input type="hidden" name="currency" value="{{ strtoupper(get_application_currency()->title) }}">
  <input type="hidden" name="currency_id" value="{{ get_application_currency_id() }}">
  <input type="hidden" name="callback_url" value="{{ route('public.payment.paypal.status') }}">
  <input type="hidden" name="return_url" value="{{ route('public.checkout.success', $token) }}">
  <ul class="list-group list_payment_method">
    @if ($localCurrency != 'BDT' && setting('payment_stripe_status') == 1)
    <li class="list-group-item">
      <input class="magic-radio js_payment_method" 
             type="radio" 
             name="payment_method" 
             id="payment_stripe" 
             value="stripe" @if (!setting('default_payment_method') || setting('default_payment_method')==\Canopy\Payment\Enums\PaymentMethodEnum::STRIPE) checked @endif 
             data-toggle="collapse" 
             data-target=".payment_stripe_wrap" 
             data-parent=".list_payment_method">
      <label for="payment_stripe" class="text-left">
        {{ setting('payment_stripe_name', trans('plugins/payment::payment.payment_via_card')) }}
      </label>
      <div class="payment_stripe_wrap payment_collapse_wrap collapse @if (!setting('default_payment_method') ||
                      setting('default_payment_method')==\Canopy\Payment\Enums\PaymentMethodEnum::STRIPE) show @endif">
        <div class="mt-2 card-checkout">
          <div class="form-group">
            <div class="stripe-card-wrapper"></div>
          </div>
          <div class="form-group @if ($errors->has('name') || $errors->has('number') ||
                          $errors->has('expiry') || $errors->has('cvc')) has-error @endif">
            <div class="row">
              <div class="form-group  col-sm-12 px-1 nice--input mt-4 mt-md-3 mb-3">
                <label for="">{{ trans('plugins/payment::payment.full_name') }}</label>
                <input placeholder="" class="form-control" id="stripe-name" type="text" data-stripe="name">
              </div>

              <div class="form-group col-sm-6 nice--input px-1 mb-3">
                <label for="">{{ trans('plugins/payment::payment.card_number') }}</label>
                <input placeholder="" class="form-control" type="text" id="stripe-number" data-stripe="number">
              </div>
              <div class="form-group col-6 col-sm-3 nice--input px-1">
                <label for="stripe-exp">{{ trans('plugins/payment::payment.mm_yy') }}</label>
                <input placeholder="" class="form-control" type="text" id="stripe-exp" data-stripe="exp">
              </div>
              <div class="form-group col-6 col-sm-3 nice--input px-1">
                <label for="stripe-cvc">{{ trans('plugins/payment::payment.cvc') }}</label>
                <input placeholder="" class="form-control" type="text" id="stripe-cvc" data-stripe="cvc">
              </div>
            </div>
          </div>

        </div>
        <div id="payment-stripe-key" data-value="{{ setting('payment_stripe_client_id') }}"></div>
      </div>
    </li>
    @endif
    @if ($localCurrency != 'BDT' && setting('payment_paypal_status') == 1)
    <li class="list-group-item">
      <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_paypal" @if (setting('default_payment_method')==\Canopy\Payment\Enums\PaymentMethodEnum::PAYPAL) checked @endif value="paypal">
      <label for="payment_paypal" class="text-left">{{ setting('payment_paypal_name', trans('plugins/payment::payment.payment_via_paypal')) }}</label>
    </li>
    @endif

    {!! apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, ['amount' => Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount + $shippingAmount, 'currency' => strtoupper(get_application_currency()->title), 'name' => null, 'localCurrency' => $localCurrency]) !!}

    @if (setting('payment_cod_status') == 1)
    <li class="list-group-item">
      <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_cod" @if (setting('default_payment_method')==\Canopy\Payment\Enums\PaymentMethodEnum::COD) checked @endif value="cod" data-toggle="collapse" data-target=".payment_cod_wrap" data-parent=".list_payment_method">
      <label for="payment_cod" class="text-left">{{ setting('payment_cod_name', trans('plugins/payment::payment.payment_via_cod')) }}</label>
      <div class="payment_cod_wrap payment_collapse_wrap collapse @if (setting('default_payment_method')==\Canopy\Payment\Enums\PaymentMethodEnum::COD) show @endif" style="padding: 15px 0;">
        {!! clean(setting('payment_cod_description')) !!}
      </div>
    </li>
    @endif
    @if (setting('payment_bank_transfer_status') == 1)
    <li class="list-group-item">
      <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_bank_transfer" @if (setting('default_payment_method')==\Canopy\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) checked @endif value="bank_transfer" data-toggle="collapse" data-target=".payment_bank_transfer_wrap" data-parent=".list_payment_method">
      <label for="payment_bank_transfer" class="text-left">{{ setting('payment_bank_transfer_name', trans('plugins/payment::payment.payment_via_bank_transfer')) }}</label>
      <div class="payment_bank_transfer_wrap payment_collapse_wrap collapse @if (setting('default_payment_method')==\Canopy\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) show @endif" style="padding: 15px 0;">
        {!! clean(setting('payment_bank_transfer_description')) !!}
      </div>
    </li>
    @endif
  </ul>
</div>

@if ($product->owner and $product->owner->allow_speed_service)
{{--<div class="form-group mb-3">
                <div class="chek-form">
                  <div class="custome-checkbox">
                    <input class="form-check-input" type="checkbox" name="speed_service" id="speed_service" value="0.15">
                    <label class="form-check-label text-muted"
                      for="speed_service"><span>{{ __('24 hour Speed Service') }}</span></label>
</div>
</div>
</div>--}}

<div class="mb-4">
  <div class="text-center mb-4">
    <h5 class="checkout-payment-title">{{ __('Delivery Type') }}</h5>
    <p class="text-muted">Select how fast you want to deliver your request</p>
  </div>
  <ul class="list-group list_delivery_method">
    <li class="list-group-item">
      <input class="magic-radio js_delivery_method" type="radio" name="delivery_method" id="delivery_free" checked value="free">
      <label for="delivery_free" class="text-left">Standard <b class="text-warning">FREE</b></label>
    </li>
    <li class="list-group-item">
      <input class="magic-radio js_delivery_method" type="radio" name="delivery_method" id="delivery_speed" value="speed">
      <label for="delivery_speed" class="text-left">Speed Service <b class="text-warning">+{{ format_price(Cart::instance('cart')->rawSubTotal() * 0.4) }}</b></label>
    </li>
  </ul>
</div>

@endif
<br>
<div class="col-12 mx-auto d-md-block" data-price="{{ format_price(Cart::instance('cart')->rawTotal(), null, true, false) }}" data-currency="{{ get_application_currency()->symbol }}" id="main-checkout-product-info">

  @include('plugins/ecommerce::themes.discounts.partials.form')
  <hr />

  <div class="totals__box">
    <div class="row price">
      <div class="col-lg-7 col-md-8 col-7">
        <p>{{ __('Subtotal') }}:</p>
      </div>
      <div class="col-lg-5 col-md-4 col-5">
        <p class="price-text sub-total-text">
          {{ format_price(Cart::instance('cart')->rawSubTotal()) }}
        </p>
      </div>
    </div>
    {{--@if (session('applied_coupon_code'))
                  <div class="row coupon-information">
                    <div class="col-lg-7 col-md-8 col-7">
                      <p>{{ __('Coupon code') }}:</p>
  </div>
  <div class="col-lg-5 col-md-4 col-5">
    <p class="price-text coupon-code-text"> {{ session('applied_coupon_code') }}
    </p>
  </div>
</div>
@endif--}}
@if ($couponDiscountAmount > 0)
<div class="row price discount-amount">
  <div class="col-lg-7 col-md-8 col-7">
    <p>{{ __('Discount amount') }}:</p>
  </div>
  <div class="col-lg-5 col-md-4 col-5">

    <p class="price-text total-discount-amount-text" data-discount="{{ format_price($couponDiscountAmount, null, true, false) }}">
      @php
          $original_price = format_price(Cart::instance('cart')->rawSubTotal(), null, true, false);
          $discounted_price = format_price($couponDiscountAmount, null, true, false);
          $total_discounted = $original_price - $discounted_price;
      @endphp
      - {{ get_application_currency()->symbol }}{{ $discounted_price }}
    </p>
  </div>
</div>
@endif
@if ($promotionDiscountAmount > 0)
<div class="row">
  <div class="col-lg-7 col-md-8 col-7">
    <p>{{ __('Promotion discount amount') }}:</p>
  </div>
  <div class="col-lg-5 col-md-4 col-5">
    <p class="price-text"> {{ format_price($promotionDiscountAmount) }} </p>
  </div>
</div>
@endif

<div class="row shipment service_fee">
  <div class="col-lg-7 col-md-8 col-7">
    <p>{{ __('Additional fee') }}:</p>
  </div>
  <div class="col-lg-5 col-md-4 col-5 float-right">
    <p class="price-text service-price-text">
      {{ format_price(0) }}
    </p>
  </div>
</div>

@if (EcommerceHelper::isTaxEnabled())
<div class="row shipment">
  <div class="col-lg-7 col-md-8 col-7">
    <p>{{ __('Tax') }}:</p>
  </div>
  <div class="col-lg-5 col-md-4 col-5 float-right">
    <p class="price-text tax-price-text">
      {{ format_price(Cart::instance('cart')->rawTax()) }}
    </p>
  </div>
</div>
@endif
<hr />
<div class="row total-price">
  <div class="col-lg-7 col-md-8 col-7">
    <p>{{ __('Total') }}:</p>
  </div>
  <div class="col-lg-5 col-md-4 col-5 float-right">
    <p class="total-text raw-total-text" data-price="{{ Cart::instance('cart')->rawTotal() }}">
      @if ($couponDiscountAmount > 0)
          {{ get_application_currency()->symbol }}{{ $total_discounted }}
      @else
          {{ format_price(Cart::instance('cart')->rawTotal()) }}
      @endif
    </p>
  </div>
</div>
</div>
</div>

<div class="form-group">
  <div class="row">
    {{-- <div class="col-md-6 d-none d-md-block" style="line-height: 53px">
                    <a class="text-info" href="{{ route('public.cart') }}"><i class="fas fa-long-arrow-alt-left"></i> {{ __('Back to cart') }}</a>
  </div> --}}
  <div class="col-12 col-md-8 mx-auto mb-3">
    <button type="submit" class="btn payment-checkout-btn payment-checkout-btn-step btn-primary btn-block float-right" data-processing-text="{{ __('Processing. Please wait...') }}" data-error-header="{{ __('Error') }}">
      {{ __('Book & Pay') }}
    </button>

    <a href="{{ url()->previous() }}" class="btn btn-link">Cancel and Return</a>

  </div>
  <div class="col-12 text-center text-muted pt-3"> <input type="checkbox" name="agree_with_terms" id="agree_with_terms" value="true" checked /> By booking, you agree to our
    <a href="{{ url('/terms') }}" target="_blank">Terms of Service</a>,
    <a href="{{ url('/privacy') }}" target="_blank">Privacy Policy</a> and
    <a href="{{ url('/refund-policy') }}" target="_blank">Refund Policy</a>
  </div>
</div>

</div>
</form>

</div> <!-- /form checkout -->

</div>
<!---------------------- start right column ---------------- -->

</div>
@else
<div class="container">
  <div class="row">
    <div class="col-12">
      @if (theme_option('logo'))
      <div class="checkout-logo">
        <a href="{{ url('/') }}" title="{{ theme_option('site_title') }}">
          <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" class="img-fluid" width="150" alt="{{ theme_option('site_title') }}" />
        </a>
      </div>
      <hr />
      @endif
      <div class="alert alert-warning" style="margin: 50px auto;">
        <span>{!! __('Sorry there are no requests. :link!', ['link' => Html::link(url('/'), __('Go Back'))]) !!}</span>
      </div>
    </div>
  </div>
</div>
@endif

<script src="{{ asset('vendor/core/plugins/payment/libraries/card/card.js') }}"></script>
@if ($localCurrency != 'BDT' && setting('payment_stripe_status') == 1)
<script src="{{ asset('https://js.stripe.com/v2/') }}"></script>
@endif
<script src="{{ asset('vendor/core/plugins/payment/js/payment.js') }}?v=1.0.2"></script>

<script>
  (function($) {
    $('.list_occasion--selector li').hide();
    $('.list_occasion--selector li.show--default').show();

    function updateCheckoutNumbers() {
      // var price = $('.total-price .raw-total-text').data('price');
      var price = $('#main-checkout-product-info').data('price');
      var symbol = $('#main-checkout-product-info').data('currency');
      var discounted_price = $('.total-discount-amount-text').data('discount');

      var total = price;
      // $('.total-price .raw-total-text').data('price', total);
      $('#main-checkout-product-info').data('price', total);

      var servicePrice = 0;
      if ($('.list_delivery_method').length) {

        if ($('input[name="delivery_method"]:checked').val() === 'speed') {
          servicePrice = total * 0.4;
        }
        $('label[for= "delivery_speed"] b').text('+ £' + (total * 0.4).toFixed(2))
      }
      var newPrice = total + servicePrice;

      if ($('.price.discount-amount').length) {
        var discountPercent = $('#default_price').val() / $('.total-discount-amount-text').data('discount');
        discountPercent = discountPercent / 100;
        var discountedAmount = total * discountPercent;
        newPrice = newPrice - discounted_price;
        $('.total-discount-amount-text').html('- ' + symbol + discounted_price.toFixed(2));
      }

      $('.service-price-text').html(symbol + servicePrice.toFixed(2));
      $('.price .sub-total-text').html(symbol + total.toFixed(2));
      $('.total-price .raw-total-text').html(symbol + newPrice.toFixed(2)); // total
    }


    $(document).on('discount-coupon-applied', function(e, data) {
      // console.log('discount-coupon-applied', e, data);
      updateCheckoutNumbers()
    });
    $(document).on('discount-coupon-removed', function(e, data) {
      // console.log('discount-coupon-removed', e, data);
      updateCheckoutNumbers()
    });


    $('input[name="target_audience"]').on('change', function(e) {
      $('input[name="target_audience"]').removeAttr('checked');
      $(this).attr('checked', true);
      var price = $('.total-price .raw-total-text').data('price');
      var price = $('#main-checkout-product-info').data('price');
      $('.list_occasion--selector li').hide();
      if ($(this).val() === 'self') {
        $('.hide_on_self').hide()
        $('input[name="recepient"]').parent().find('label').text('What\'s your name?');
      } else {
        $('.hide_on_self').show()
        $('input[name="recepient"]').parent().find('label').text('What\'s their name?');
      }

      if ($(this).val() === 'corporate') {
        $('.list_occasion--selector li.show--business').show();
        var extra = parseFloat($('#business_price').val());
        var total = extra > 0 ? extra : price;

      } else {
        $('.list_occasion--selector li.show--default').show();
        var total = $('#default_price').val(); // * 0.05;
      }
      //$('.total-price .raw-total-text').data('price', total);
      $('#main-checkout-product-info').data('price', total);
      var servicePrice = 0;
      if ($('.list_delivery_method').length) {

        if ($('input[name="delivery_method"]:checked').val() === 'speed') {
          servicePrice = total * 0.4;
        }
        $('label[for= "delivery_speed"] b').text('+ '+ symbol + (total * 0.4).toFixed(2))
      }
      var newPrice = total + servicePrice;
      if ($('.price.discount-amount').length) {
        var discountPercent = $('#default_price').val() / $('.total-discount-amount-text').data('discount');
        discountPercent = discountPercent / 100;
        var discountedAmount = total * discountPercent;
        $('.total-discount-amount-text').html('- ' + symbol + discountedAmount.toFixed(2));
        newPrice = newPrice - discountedAmount;
      }

      $('.service-price-text').html(symbol + servicePrice.toFixed(2));
      $('.price .sub-total-text').html(symbol + total.toFixed(2));  // change this
      $('.total-price .raw-total-text').html(symbol + newPrice.toFixed(2))
    });

    if ($('.list_delivery_method').length) {
      $('input[name="delivery_method"]').on('change', function(e) {
        $('input[name="delivery_method"]').removeAttr('checked');
        $(this).attr('checked', true);
        let el = $(this);
        var price = $('.total-price .raw-total-text').data('price');
        var price = $('#main-checkout-product-info').data('price');
        var newPrice = price;
        var servicePrice = 0;
        const audience = $('input[name="target_audience"]').val();

        if (audience === 'corporate') {
          var extra = parseFloat($('#business_price').val());
          var total = extra > 0 ? extra : price;
        } else {
          var total = price; // * 0.05;
        }


        if (el.val() === 'speed') {
          servicePrice = total * 0.4;
          newPrice = total + (total * 0.40);
        }

        if ($('.price.discount-amount').length) {
          var discountPercent = $('#default_price').val() / $('.total-discount-amount-text').data('discount');
          discountPercent = discountPercent / 100;
          var discountedAmount = total * discountPercent;
          $('.total-discount-amount-text').html('- '+ symbol + discountedAmount.toFixed(2));
          newPrice = newPrice - discountedAmount;
        }

        $('.service-price-text').html(symbol + servicePrice.toFixed(2));
        $('.price .sub-total-text').html(symbol + total.toFixed(2)); // change this
        $('.total-price .raw-total-text').html(symbol + newPrice.toFixed(2))
      });

    }


    if ($("#speed_service").length) {
      $("#speed_service").on('change', function(e) {

        var price = $('.total-price .raw-total-text').data('price');
        var price = $('#main-checkout-product-info').data('price');
        var newPrice = price;


        if ($(this).val() === 'corporate') {
          var extra = parseFloat($('#business_price').val());
          var total = extra > 0 ? extra : price;
        } else {
          var total = price; // * 0.05;

        }
        if ($(this).prop('checked')) {
          total = total + (price * 0.15);
          newPrice = price + (price * 0.15);
        }
        if ($('.price.discount-amount').length) {
          var discountPercent = $('#default_price').val() / $('.total-discount-amount-text').data('discount');
          discountPercent = discountPercent / 100;
          var discountedAmount = total * discountPercent;
          $('.total-discount-amount-text').html('- ' + symbol + discountedAmount.toFixed(2));
          newPrice = newPrice - discountedAmount;
        }

        $('.service-price-text').html(symbol + total.toFixed(2));
        $('.total-price .raw-total-text').html(symbol + newPrice.toFixed(2))

      });
    }

    // agreement checked function
    // $('#agree_with_terms').on('change',function(){

    // });

    $("#agree_with_terms").click(function() {
        if(!$(this).is(":checked"))
        $('.payment-checkout-btn').attr('disabled',true);
        if($(this).is(":checked"))
        $('.payment-checkout-btn').attr('disabled',false);
    });
  })(jQuery);
</script>

@stop
