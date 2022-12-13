@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
  @php Theme::set('pageName', __('Request information')) @endphp
  <div class="card">
    <div class="card-header">
        <div class="talent-card">
          @foreach ($order->products as $key => $orderProduct)
            @php
              $product = get_products([
                  'condition' => [
                      'ec_products.status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED,
                      'ec_products.id' => $orderProduct->product_id,
                  ],
                  'take' => 1,
                  'select' => ['ec_products.id', 'ec_products.images', 'ec_products.talent_id', 'ec_products.name', 'ec_products.price', 'ec_products.sale_price', 'ec_products.sale_type', 'ec_products.start_date', 'ec_products.end_date', 'ec_products.sku', 'ec_products.is_variation'],
              ]);
            @endphp

            <div class="talent-card--image">
              <img src="{{ RvMedia::getImageUrl($product->owner->photo, 'thumb', false, RvMedia::getDefaultImage()) }}" width="50"
                alt="{{ $product->name }}">
            </div>
            <div class="talent-card--title">{{ __('Video Request to') }} <strong>{{ $product->name }}</strong></div>
            <div class="talent-card--action">
                <a class="btn btn-dark btn-sm" href="{{ $product->url }}">
                    {{ __('View profile') }}
                </a>
            </div>
          @endforeach
        </div>
    </div>
    <div class="card-body">
      <div class="customer-order-detail">
        <div class="row">
          <div class="col-md-7">
            <div class="inner">

              <h6 class="p-3">{{ __('Request Details') }}</h6>
              <div class="request--details">
                <div class="col-12">
                  <span class="text-muted">{{ __('Request no') }}</span> <span
                    class="order-detail-value">{{ get_order_code($order->id) }}</span>
                </div>
                <div class="col-12">
                  <span class="text-muted">{{ __('Time') }}</span> <span
                    class="order-detail-value">{{ $order->created_at->format('h:m d M Y') }}
                    <small class="text-muted">({{ $order->created_at->diffForHumans() }})</small></span>
                </div>
                <div class="col-12">
                  <span class="text-muted">{{ __('Requested from') }}</span> <span
                    class="order-detail-value">{{ $order->from ?? 'Myself'}}</span>
                </div>
                <div class="col-12">
                  <span class="text-muted">{{ __('Requested for') }}</span> <span
                    class="order-detail-value">{{ $order->recepient }}</span>
                </div>

                <div class="col-12">
                  <span class="text-muted">{{ __('Occasion') }}</span> <span
                    class="order-detail-value">{{ $order->occasion->name ?? 'None' }}</span>
                </div>

                <div class="col-12">
                  <span class="text-muted">{{ __('Instructions') }}</span> <span
                    class="order-detail-value">{{ $order->description }}</span>
                </div>


                <div class="col-12">
                  <span class="text-muted">{{ __('Request status') }}</span> <span
                    class="order-detail-value">{!! $order->status->toHtml() !!}</span>
                </div>
                  <div class="col-12">
                      <span class="text-muted">{{ __('Delivery Type') }}</span>

                      <span class="order-detail-value">
                        @if ($order->is_speed_service)
                              Fast (24h Delivery)
                          @else
                              Standard (3-5 Working Days)
                          @endif
                    </span>
                  </div>
                <hr>

                <div class="col-12">
                  <span class="text-muted">{{ __('Payment method') }}</span> <span class="order-detail-value">
                    {!! $order->payment->payment_channel->label() !!}
                  </span>
                </div>
                <div class="col-12">
                  <span class="text-muted">{{ __('Payment status') }}</span> <span
                    class="order-detail-value">{!! $order->payment->status->toHtml() !!}</span>
                </div>

                @if (EcommerceHelper::isTaxEnabled())
                  <div class="col-12">
                    <span class="text-muted">{{ __('Tax') }}</span> <span class="order-detail-value">
                      {{ format_price($order->tax_amount, $order->currency_id) }} </span>
                  </div>
                @endif

                <div class="col-12">
                  <span class="text-muted">{{ __('Additional fees') }}</span> <span class="order-detail-value">
                    {{ format_price($order->shipping_amount, $order->currency_id) }} </span>
                </div>
                <div class="col-12">
                  <span class="text-muted">{{ __('Amount') }}</span> <span class="order-detail-value">
                    {{ format_price($order->amount, $order->currency_id) }} </span>
                </div>
              </div>
            </div>

              <div class="review_form field_form mt-3">
                  @if (auth('customer')->check() && $order->status == 'completed')
                      <h6 class="p3">{{ __('Add a review') }}</h6>

                      {!! Form::open(['route' => 'public.reviews.create', 'method' => 'post', 'class' => 'row form-review-product']) !!}
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                      <input type="hidden" name="star" value="1">
                      <div class="form-group col-12">
                          <div class="star_rating">
                              <span data-value="1"><i class="ion-star"></i></span>
                              <span data-value="2"><i class="ion-star"></i></span>
                              <span data-value="3"><i class="ion-star"></i></span>
                              <span data-value="4"><i class="ion-star"></i></span>
                              <span data-value="5"><i class="ion-star"></i></span>
                          </div>
                      </div>
                      <div class="form-group col-12 nice--input">
                          <label>{{ __('Write your review') }}</label>
                    <textarea class="form-control" name="comment" id="txt-comment" rows="4"
                              @if (!auth('customer')->check()) disabled @endif></textarea>
                      </div>
                      <div class="form-group col-12">
                          <button type="submit" class="btn btn-primary btn-rounded @if (!auth('customer')->check()) btn-disabled @endif" @if (!auth('customer')->check())
                          disabled @endif name="submit" value="Submit">Submit Review</button>
                      </div>
                      <br>
                      <div class="success-message text-success" style="display: none;">
                          <span></span>
                      </div>
                      <div class="error-message text-danger" style="display: none;">
                          <span></span>
                      </div>
                      {!! Form::close() !!}
                  @endif
              </div>


          </div>
          <div class="col-md-5">
              @if ($order->canBeCanceled())
              <div class="row mb-3">
                  <div class="col px-4">
                      {{-- <a href="{{ route('customer.print-order', $order->id) }}"
                      class="btn btn-fill-out btn-sm">{{ __('Print order') }}</a> --}}
                      @if ($order->status == 'pending')
                          <a href="{{ route('customer.orders.modify', $order->id) }}"
                             class="btn btn-primary btn-sm">{{ __('Modify') }}</a>
                      @endif
                      {{--<a href="{{ route('customer.orders') }}" class="btn btn-dark btn-sm">{{ __('Go Back') }}</a>--}}

                      <a href="{{ route('customer.orders.cancel', $order->id) }}"
                         class="btn btn-danger btn-sm l-auto">{{ __('Cancel') }}</a>
                  </div>
              </div>
              @endif
              <div class="upload--video--section">
                  <div class="card-body">
            @if ($order->video and in_array($order->status, ['completed']))
              <div class="final--video">
                <video width="480" height="640" controls="">
                  <source src="{{ RvMedia::getImageUrl($order->video, null, false) }}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
              <div class="text-center p-3">
                <a href="{{ route('customer.download-video', $order->id) }}" class="btn btn-info">
                  <i class="fa fa-download"></i> {{ trans('plugins/ecommerce::order.download_video') }}
                </a>
              </div>
            @else
              <div class="video--placeholder">
                <i class="fa fa-video-camera"></i>
                <span>Your video will appear here once completed</span>
              </div>
            @endif
                  </div>
              </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
