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
        <i class="fas fa-check-circle text-primary" aria-hidden="true"></i>
      </div>
      <div class="col-12 mt-4 text-secondary">
        <h3 class="thank-you-sentence">
          {{ __('Your request was sent successfully') }}
        </h3>
        <p>{{ __('Request number') }}: {{ get_order_code($order->id) }} </p>
        <p>
          {{ __('Thank you for your request. The money have been blocked on your card until celebrity accepts your request') }}
      </div>
    </div>


    <!-- for mobile device display -->
    {{-- <div class="d-none">
      <div class="row show-cart-row">
        <div class="col-6">
          <a class="show-cart-link" href="javascript:void(0);" data-toggle="collapse"
            data-target="#cart-item">{{ __('Order information') }} <i class="fa fa-angle-down"
              aria-hidden="true"></i></a>
        </div>
        <div class="col-6">
          <p class="text-right mobile-total"> {{ format_price($order->amount) }} </p>
        </div>
      </div>
      <div id="cart-item" class="collapse">

        @foreach ($order->products as $orderProduct)
          @php
            $product = get_products([
                'condition' => [
                    'ec_products.status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED,
                    'ec_products.id' => $orderProduct->product_id,
                ],
                'take' => 1,
                'select' => ['ec_products.id', 'ec_products.images', 'ec_products.name', 'ec_products.price', 'ec_products.sale_price', 'ec_products.sale_type', 'ec_products.start_date', 'ec_products.end_date', 'ec_products.sku', 'ec_products.is_variation'],
            ]);
          @endphp

          <div class="row cart-item">
            <div class="col-3">
              <div class="checkout-product-img-wrapper">
                <img class="item-thumb img-thumbnail img-rounded"
                  src="{{ RvMedia::getImageUrl($product->original_product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                  alt="{{ $product->name }} ({{ $product->sku }})">
                <span class="checkout-quantity">{{ $orderProduct->qty }}</span>
              </div>
            </div>
            <div class="col-4">
              <p style="margin-bottom: 0;">{{ $product->name }}</p>
              <p style="margin-bottom: 0">
                <small>
                  @php
                    $attributes = get_product_attributes($product->id);
                  @endphp

                  @if (!empty($attributes))
                    @foreach ($attributes as $attribute)
                      @if (!$loop->last)
                        {{ $attribute->attribute_set_title }}: {{ $attribute->title }},
                      @else
                        {{ $attribute->attribute_set_title }}: {{ $attribute->title }}
                      @endif
                    @endforeach
                  @endif
                </small>
              </p>

              @if (!empty($orderProduct->options) && is_array($orderProduct->options))
                @foreach ($orderProduct->options as $option)
                  @if (!empty($option['key']) && !empty($option['value']))
                    <p class="mb-0"><small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small>
                    </p>
                  @endif
                @endforeach
              @endif
            </div>
            <div class="col-4 float-right">
              <p>{{ $orderProduct->price }}</p>
            </div>
          </div> <!--  /item -->
        @endforeach

        <hr />
        <div class="row">
          <div class="col-6">
            <p>{{ __('Subtotal') }}</p>
          </div>
          <div class="col-6 float-right">
            <p class="total-text raw-total-text"> {{ format_price($order->sub_total) }} </p>
          </div>
        </div>


        <hr />
        <div class="row">
          <div class="col-6">
            <p>{{ __('Shipping fee') }}@if ($order->shipping_amount == 0 && $order->discount_amount == 0 && $order->coupon_code)
                ({{ __('Using coupon code') }} <strong>{{ $order->coupon_code }}</strong>)@endif:</p>
          </div>
          <div class="col-6 float-right">
            <p class="total-text raw-total-text"> {{ format_price($order->shipping_amount) }} </p>
          </div>
        </div>

        @if ($order->discount_amount !== null)
          <div class="row">
            <div class="col-6">
              <p>{{ __('Discount') }}:</p>
            </div>
            <div class="col-6 float-right">
              <p class="total-text raw-total-text"> {{ format_price($order->discount_amount) }} </p>
            </div>
          </div>
        @endif

        @if (EcommerceHelper::isTaxEnabled())
          <div class="row shipment">
            <div class="col-lg-7 col-md-8 col-5">
              <p>{{ __('Tax') }}:</p>
            </div>
            <div class="col-lg-5 col-md-4 col-5 float-right">
              <p class="total-text raw-total-text"> {{ format_price($order->tax_amount) }} </p>
            </div>
          </div>
        @endif

        <hr />
        <div class="row">
          <div class="col-6">
            <p>{{ __('Total') }}:</p>
          </div>
          <div class="col-6 float-right">
            <p class="total-text raw-total-text"> {{ format_price($order->amount) }} </p>
          </div>
        </div>
      </div>

    </div> --}}
    <!-- /mobile display -->
    <hr />


    <div class="d-none">


      <!-- total info -->
      <hr />
      <div class="row total-price">
        <div class="col-lg-7 col-md-8 col-5">
          <p>{{ __('Sub amount') }}:</p>
        </div>
        <div class="col-lg-5 col-md-4 col-5 float-right">
          <p class="total-text raw-total-text"> {{ format_price($order->sub_total) }} </p>
        </div>
      </div>

      <hr />
      <div class="row total-price">
        <div class="col-lg-7 col-md-8 col-5">
          <p>{{ __('Additional fees') }}@if ($order->shipping_amount == 0 && $order->discount_amount == 0 && $order->coupon_code)
              ({{ __('Using coupon code') }} <strong>{{ $order->coupon_code }}</strong>) @endif
            :
          </p>
        </div>
        <div class="col-lg-5 col-md-4 col-5 float-right">
          <p class="total-text raw-total-text">{{ format_price($order->shipping_amount) }} </p>
        </div>
      </div>

      @if ($order->discount_amount !== null)
        <div class="row total-price">
          <div class="col-lg-7 col-md-8 col-5">
            <p>{{ __('Discount') }}:</p>
          </div>
          <div class="col-lg-5 col-md-4 col-5 float-right">
            <p class="total-text raw-total-text">{{ format_price($order->discount_amount) }} </p>
          </div>
        </div>
      @endif

      @if (EcommerceHelper::isTaxEnabled())
        <div class="row total-price">
          <div class="col-6">
            <p>{{ __('Tax') }}:</p>
          </div>
          <div class="col-6 float-right">
            <p class="total-text raw-total-text">{{ format_price($order->tax_amount) }}</p>
          </div>
        </div>
      @endif

      <hr />
      <div class="row total-price">
        <div class="col-lg-7 col-md-8 col-5">
          <p>{{ __('Total amount') }}:</p>
        </div>
        <div class="col-lg-5 col-md-4 col-5 float-right">
          <p class="total-text raw-total-text"> {{ format_price($order->amount) }} </p>
        </div>
      </div>
    </div>


    {{--<hr />
    {{-- <div class="order-customer-info">
      <h3> {{ __('Customer information') }}</h3>
      <p>{{ __('Full name') }}: <span class="order-customer-info-meta">{{ $order->address->name }}</span>
      </p>
      <p>{{ __('Phone') }}: <span class="order-customer-info-meta">{{ $order->address->phone }}</span></p>
      <p>{{ __('Email') }}: <span class="order-customer-info-meta">{{ $order->address->email }}</span>
      </p>
      <p>{{ __('Address') }}: <span class="order-customer-info-meta">{{ $order->address->address }},
          {{ $order->address->city }}, {{ $order->address->state }}, {{ $order->address->country_name }}</span>
      </p>
      <p>{{ __('Shipping method') }}: <span
          class="order-customer-info-meta">{{ $order->shipping_method_name }}</span>
      </p>
      <p>{{ __('Payment method') }}: <span
          class="order-customer-info-meta">{{ $order->payment->payment_channel->label() }}</span>
      </p>
      <p>{{ __('Payment status') }}: <span class="order-customer-info-meta"
          style="text-transform: uppercase">{!! $order->payment->status->toHtml() !!}</span>
      </p>
    </div>
    <hr /> --}}

    <div class="row">

        <div class="col-12 text-center">
            <h4 class="text-white">Forgot something?</h4>
            <p class="text-secondary mb-4">No worries, you can update your request at any time before the talent accepts it</p>
            <a href="{{ route('customer.orders.modify', $order->id) }}" class="btn btn-primary payment-checkout-btn">
                {{ __('Modify My Request') }} </a>
        </div>

      <div class="col-12 mt-4 text-center">
        <a href="{{ url('/customer/orders') }}" class="btn btn-secondary">
          {{ __('View My Requests') }} </a>
      </div>
      <div class="col-12 mt-4 text-center">
        <a href="{{ url('/') }}" class="btn btn-link"> {{ __('Explore more') }} </a>
      </div>
    </div>

  </div>
  <!---------------------- start right column ------------------>

@stop
