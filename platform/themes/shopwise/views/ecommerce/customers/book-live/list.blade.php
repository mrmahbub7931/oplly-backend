@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')

    @php Theme::set('pageName', __('Book Live')) @endphp
    <div class="card" id="book-live">
        <div class="card-header">
            <h3>{{ __('Live Bookings') }}</h3>
            <div class="row">
                <div class="col-12 col-md-4 ml-auto">
                    <a href="{{route('talent.change-availability')}}"><i class="fas fa-calendar"></i>Change Availability</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <book-live url="{{ route('talent.get-availability') }}"></book-live>
        </div>
    </div>
    <script src="{{ asset('vendor/core/plugins/ecommerce/js/book-live.js') }}"></script>
@endsection
