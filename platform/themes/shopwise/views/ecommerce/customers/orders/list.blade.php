@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
  @php Theme::set('pageName', __('Requests')) @endphp

  <div class="card">
    <div class="card-header">
      <h3>{{ __('My Requests') }}</h3>
    </div>
    <div class="card-body">
        <div class="requests-list">
            @if (count($orders) > 0)
              @foreach ($orders as $order)
                @if (count($order->products) > 0)
                <div class="requests-list--item">
                    <div class="requests-list--image">
                        <a href="{{ route('customer.orders.view', $order->id) }}">
                        @if ($order->talent)
                            <img src="{{ RvMedia::getImageUrl($order->talent->photo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                 alt="{{ $order->products[0]->product->name }}" />
                        @else
                            <img src="{{ RvMedia::getImageUrl($order->products[0]->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                 alt="{{$order->products[0]->product->name }}" />
                        @endif
                        </a>
                    </div>
                    <div class="requests-list--info">
                        <a href="{{ route('customer.orders.view', $order->id) }}">
                            <div class="text-white">{{ $order->products[0]->product->name }}</div>
                            <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }} ({{ $order->created_at->diffForHumans() }})</small>
                        </a>
                        @if ($order->is_speed_delivery)
                            <div class="requests-list--delivery-info">
                                <i class="fa fa-lighting"></i>
                            </div>
                        @endif
                    </div>
                    <div class="requests-list--price">
                        {{ format_price($order->amount) }}
                    </div>
                    <div class="requests-list--status-info">
                        {!! $order->status->toHtml() !!}
                    </div>
                    <div class="requests-list--action">
                        <a class="btn btn-dark btn-sm" href="{{ route('customer.orders.view', $order->id) }}">
                            {{ __('View') }}
                        </a>
                    </div>
                </div>
                @endif
              @endforeach
            @else
                <div class="no--record">
                    <h4 class="text-muted text-center">{{ __('You don\'t have any requests yet') }}</h4>
                </div>
            @endif
      </div>
      <div class="mt-3 justify-content-center">
        {!! $orders->links() !!}
      </div>
    </div>
  </div>
@endsection
