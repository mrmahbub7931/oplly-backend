@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @php Theme::set('pageName', __('Book Live')) @endphp
    <div class="card" id="book-live">
        <div class="card-header">
            <h3>{{ __('Availability') }}</h3>
            <div class="row">
                <div class="col-12 col-md-4">
                    <a href="{{back()}}">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <book-live-settings></book-live-settings>
        </div>
    </div>
    <script src="{{ asset('vendor/core/plugins/ecommerce/js/book-live.js') }}"></script>
@endsection
