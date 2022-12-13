@extends('core/base::layouts.master')
@section('content')
    <div id="dashboard-alerts">
        <verify-license-component verify-url="{{ route('settings.license.verify') }}" setting-url="{{ route('settings.options') }}"></verify-license-component>
    </div>
    {!! apply_filters(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, null) !!}
    <div class="row">
        {!! apply_filters(DASHBOARD_FILTER_TOP_BLOCKS, null) !!}
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ format_price($count['revenueToday']) }}">0</span>
                        </h3>
                        <small>Revenue (Today)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ format_price($count['revenueWeek']) }}">0</span>
                        </h3>
                        <small>Revenue (Weekly)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ format_price($count['revenueMonth']) }}">0</span>
                        </h3>
                        <small>Revenue (Monthly)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ format_price($count['revenueYear']) }}">0</span>
                        </h3>
                        <small>Revenue (Yearly)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ $count['requestDay'] }}">0</span>
                        </h3>
                        <small>Request (Today)</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ $count['requestWeek'] }}">0</span>
                        </h3>
                        <small>Request (Weekly)</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ $count['requestMonth'] }}">0</span>
                        </h3>
                        <small>Request (Monthly)</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            {{-- {{ format_price($count['revenue'], true) }} --}}
                            <span data-counter="counterup" data-value="{{ $count['requestYear'] }}">0</span>
                        </h3>
                        <small>Request (Yearly)</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="list_widgets" class="row">
        @foreach ($userWidgets as $widget)
            {!! $widget !!}
        @endforeach
        <div class="clearfix"></div>
    </div>

    @if (count($userWidgets) > 0)
        <a href="#" class="manage-widget"><i class="fa fa-plus"></i> {{ trans('core/dashboard::dashboard.manage_widgets') }}</a>
        @include('core/dashboard::partials.modals', compact('widgets'))
    @endif

@stop
