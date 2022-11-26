@extends('plugins/ecommerce::orders.master')
@section('content')
@php Theme::set('pageName', __('Request information')) @endphp


<div class="col-12">
  <!-- <div class="card-header">
    <h3>{{ __('Talent Information') }}</h3>
  </div> -->
  {!! Form::open(['route' => ['customer.orders.modify', $order->id], 'files' => true]) !!}


  @foreach ($order->products as $key => $orderProduct)
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
  <div class="form-checkout">
    <h3>{{ __('Video Request from') }} {{ $product->name }}</h3>
    <div class="talent__image">
      <img src="{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}" width="50" alt="{{ $product->name }}">
    </div>

    @endforeach

    <div class="customer-order-detail">
      <div class="row">


        <div class="col-12 col-md-8 mx-auto">


          <div class="form-group mb-3">
            <div class="text-center mb-4">
              <h5 class="checkout-payment-title">{{ __('This is for') }}</h5>
              <p class="text-muted">Please choose</p>
            </div>
          </div>

          <div class="form-group  @if ($errors->has('target_audience')) has-error @endif">
            <div class="btn-group pills--selector" role="group" aria-label="target_audience" id="target_audience_select">
              <input type="radio" class="btn-check" name="target_audience" id="target_audience_him" autocomplete="off" @if ($order->target_audience == 'single') checked @endif value="single">
              <label class="btn btn-outline-primary" for="target_audience_him">
                <span>
                  <i class="fas fa-user"></i>
                </span>
                Someone else</label>

              <input type="radio" class="btn-check" name="target_audience" id="target_audience_self" @if ($order->target_audience == 'self') checked @endif
              autocomplete="off" value="self">
              <label class="btn btn-outline-primary" for="target_audience_self">
                <span>
                  <i class="fas fa-star"></i>
                </span>
                For Me</label>

              @if ($product->talent and $product->talent->allow_business)
              <input type="radio" class="btn-check" name="target_audience" id="target_audience_corporate" @if ($order->target_audience == 'corporate') checked @endif
              autocomplete="off" value="corporate">
              <label class="btn btn-outline-primary" for="target_audience_corporate">
                <span>
                  <i class="fas fa-briefcase"></i>
                </span>
                Business</label>
              <input type="hidden" id="business_price" name="business_price" value="{{ $product->talent->business_price }}">
              @endif
            </div>
          </div>
          <input type="hidden" name="service_fee" value="0.05">

          <div class="form-group mb-3 nice--input @if ($errors->has('recepient')) has-error @endif">
            <label for="recepient">{{ __('What\'s their name?') }}</label>
            <input type="text" name="recepient" id="recepient" class="form-control address-control-item checkout-input" value="{{ $order->recepient }}">
            {!! Form::error('recepient', $errors) !!}
          </div>

          <div class="form-group mb-3 hide_on_self nice--input  @if ($errors->
                    has('from')) has-error @endif">
            <label for="from">{{ __('Who is it from?') }}</label>
            <input type="text" name="from" id="from" class="form-control address-control-item checkout-input" value="{{ $order->from }}">
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
              <li><label class="btn" for="occasion_{{ $occasion->id }}">
                  <input type="radio" class="btn-check" name="occasion_id" id="occasion_{{ $occasion->id }}" @if ($order->occasion->id == $occasion->id) checked @endif
                  autocomplete="off" value="{{ $occasion->id }}">
                  <img src="{{ RvMedia::getImageUrl($occasion->image, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $occasion->name }}" />
                  <span>{{ $occasion->name }}</span></label>
              </li>
              @endforeach
            </ul>
          </div>


          <div class="form-group">

            <div class="text-center mb-4">
              <h5 class="checkout-payment-title">{{ __('Provide Instructions') }}</h5>
              <p class="text-muted">Provide your instructions on what you want in the message</p>
            </div>
          </div>
          <div class="form-group mb-3 nice--input  @if ($errors->has('description')) has-error @endif">
            <label for="description">Instructions</label>
            <textarea name="description" id="description" rows="3" class="form-control">{{ $order->description }}</textarea>
            {!! Form::error('description', $errors) !!}
          </div>


          <div class="form-group mb-3">
            <div class="chek-form">
              <div class="custome-checkbox">
                <input class="form-check-input" type="checkbox" name="make_public" id="make_public-me" value="1" @if ($order->make_public) checked @endif>
                <label class="form-check-label text-muted" for="make_public-me"><span>{{ __('Show this video on public profile') }}</span></label>
              </div>
            </div>
          </div>

          <br>

        </div>


      </div>


      <hr>
      <div class="row">
        <div class="col px-4">
          {{-- <a href="{{ route('customer.print-order', $order->id) }}"
          class="btn btn-fill-out btn-sm">{{ __('Print order') }}</a> --}}
          {{-- @if ($order->canBeCanceled())
              <a href="{{ route('customer.orders.modify', $order->id) }}"
          class="btn btn-primary btn-sm">{{ __('Modify') }}</a>
          @endif --}}



          <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>


          <a href="{{ route('customer.orders') }}" class="btn btn-dark btn-sm">{{ __('Go Back') }}</a>


          @if ($order->canBeCanceled())
          <a href="{{ route('customer.orders.cancel', $order->id) }}" class="btn btn-danger btn-sm l-auto">{{ __('Cancel') }}</a>
          @endif



        </div>
      </div>
    </div>


    {!! Form::close() !!}
  </div>
</div>
</div>

<script>
  (function($) {

    $('input[name="target_audience"').on('change', function(e) {
      var price = $('.total-price .raw-total-text').data('price');

      if ($(this).val() === 'self') {
        $('.hide_on_self').hide()
        $('input[name="recepient"]').parent().find('label').text('What\'s your name?');
      } else {
        $('.hide_on_self').show()
        $('input[name="recepient"]').parent().find('label').text('What\'s their name?');
      }

      if ($(this).val() === 'corporate') {
        var extra = parseFloat($('#business_price').val());
        var total = extra > 0 ? extra : price;
      } else {
        var total = price; // * 0.05;
      }

      if ($("#speed_service").length && $("#speed_service").is(':checked')) {
        total = total + (price * 0.15);
      }

      $('.service-price-text').html('£' + total.toFixed(2))
    });
    if ($("#speed_service").length) {
      $("#speed_service").on('change', function(e) {

        var price = $('.total-price .raw-total-text').data('price');
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
        $('.service-price-text').html('£' + total.toFixed(2));
        $('.total-price .raw-total-text').html('£' + newPrice.toFixed(2))

      });
    }
  })(jQuery);
</script>

@endsection