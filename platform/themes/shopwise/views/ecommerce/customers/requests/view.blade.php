@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
  @php Theme::set('pageName', __('Request information')) @endphp
  <div class="card">
    <div class="card-header">




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
        <h3>{{ __('Video Request from') }} {{ $order->user->name }}</h3>


      @endforeach




    </div>
    <div class="card-body">
      <div class="customer-order-detail">
        <div class="row">
          <div class="col-md-7">
            <div class="inner">
              <div class="order-slogan">

              </div>


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
                  <span class="text-muted">{{ __('Requested type') }}</span> <span
                    class="order-detail-value">{{ $order->target_audience }}</span>
                </div>
                <div class="col-12">
                  <span class="text-muted">{{ __('Requested from') }}</span> <span
                    class="order-detail-value">{{ $order->from ?? $order->user->name }}</span>
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


                {{--<div class="col-12">
                  <span class="text-muted">{{ __('Payment method') }}</span> <span class="order-detail-value">
                    {!! $order->payment->payment_channel->label() !!}
                  </span>
                </div>
                <div class="col-12">
                  <span class="text-muted">{{ __('Payment status') }}</span> <span
                    class="order-detail-value">{!! $order->payment->status->toHtml() !!}</span>
                </div>--}}


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
                <div class="col-12">
                  <span class="text-muted">{{ __('Amount Paid') }}</span> <span class="order-detail-value">
                    {{ format_price($order->amount, $order->currency_id) }} </span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-5">
              <div class="row mb-4">


                      @if ($order->status == 'processing' || $order->status == 'accepted' and $order->video)
                      <div class="col-12">
                          <a href="{{ route('talent.requests.release', $order->id) }}"
                             class="btn btn-primary btn-block">{{ __('Release') }}</a>
                      </div>
                      @endif


                      {{-- <a href="{{ route('customer.print-order', $order->id) }}"
                      class="btn btn-fill-out btn-sm">{{ __('Print order') }}</a> --}}
                      @if ($order->canBeCanceled() and $order->status == 'pending')
                      <div class="col-12 col-md-6">
                          <a href="{{ route('talent.requests.accept', $order->id) }}"
                             class="btn btn-primary btn-block">{{ __('Accept') }}</a>
                      </div>

                      @endif

                      @if ($order->canBeCanceled() and $order->status == 'pending')
                      <div class="col-12 col-md-6">
                          <a href="{{ route('talent.requests.reject', $order->id) }}"
                             class="btn btn-dark btn-block">{{ __('Decline') }}</a>
                      </div>

                      @endif

              </div>
            <div class="upload--video--section">
              <div class="card-body">
                  @if($order->status == 'accepted' || $order->status == 'processing')
                    @if (!$order->video)
                        <div class="p-2">
                            <div class="h4 text-center">Upload your video here</div>
                        </div>

                      <div id="request--video-upload" data-token="{{csrf_token()}}" data-action="{{ route('talent.requests.upload-video', $order->id) }}"></div>
                      <button class="UppyModalOpenerBtn" style="display: none;">Open Uppy Dashboard Modal</button>
                      <div class="DashboardContainer"></div>
                    @else
                      <div class="final--video with--watermark">

                        <video width="480" height="640" controls="">
                          <source src="{{ RvMedia::getImageUrl($order->video, null, false) }}" type="video/mp4">
                          Your browser does not support the video tag.
                        </video>

                      </div>
                    @endif
                  @elseif ($order->status == 'completed')
                      <div class="text-center p-1">
                        <a href="{{ route('talent.requests.download-video', $order->id) }}" class="btn btn-primary btn-block">
                          <i class="fa fa-download"></i> Download video
                        </a>
                      </div>
                  @else
                      <div class="video--placeholder">
                          <i class="fa fa-times"></i>
                          <span>Your have {{ $order->status }} this request</span>
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
