@extends('plugins/ecommerce::orders.master')
@section('title')
  {{ __('Thank you for your request. Request number :id', ['id' => get_order_code($order->id)]) }}
@stop
@section('content')
  <div class="col-lg-7 col-md-9 col-12 mx-auto">

    @if (theme_option('logo'))
      <div class="checkout-logo">
        <a href="{{ url('/') }}" title="{{ theme_option('site_title') }}">
          <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" class="img-fluid" width="150"
            alt="{{ theme_option('site_title') }}" />
        </a>
      </div>
      <hr />
    @endif
    @foreach ($order->products as $key => $orderProduct)
      @php

        $product = get_products([
            'condition' => [
                'ec_products.status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED,
                'ec_products.id' => $orderProduct->product_id,
            ],
            'take' => 1,
            // 'select' => ['ec_products.id', 'ec_products.images', 'ec_products.name', 'ec_products.price', 'ec_products.sale_price', 'ec_products.sale_type', 'ec_products.start_date', 'ec_products.end_date', 'ec_products.sku', 'ec_products.is_variation'],
        ]);
      @endphp
      <!-- order detail item -->
      <div class="row product-item">
        <div class="col-lg-4 col-md-6 mx-auto">
          <div class="checkout-product-img-wrapper">
            <img class="item-thumb img-thumbnail img-rounded"
              src="{{ RvMedia::getImageUrl($product->owner->photo ?? $product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
              alt="{{ $product->name }} ({{ $product->sku }})">

          </div>
        </div>
        <div class="col-12 text-center mt-3">
          <h3 style="margin-bottom: 0;" class="text-white">{{ $product->name }}</h3>
        </div>
        {{-- <div class="col-lg-12 text-center">
          <p class="price-text">
            <span>{{ format_price($orderProduct->price) }}</span>
          </p>
        </div> --}}
      </div> <!--  /order item -->
    @endforeach
    <div class="thank-you row text-white text-center">
      <div class="col-12" style="padding-left: 0;">
        <i class="fas fa-times-circle text-secondary" aria-hidden="true"></i>
      </div>
      <div class="col-12 mt-4 text-secondary">
        <h3 class="thank-you-sentence">
          {{ __('Could not process payment for your request') }}
        </h3>
        <p>{{ __('Request number') }}: {{ get_order_code($order->id) }} </p>
      </div>
    </div>

    <hr />


    <div class="row">

        <div class="col-12 text-center">
            <a href="{{ route('public.checkout.information', $order->token) }}" class="btn btn-primary payment-checkout-btn">
                {{ __('Try again') }} </a>
        </div>
    </div>

  </div>
  <!---------------------- start right column ------------------>

@stop
