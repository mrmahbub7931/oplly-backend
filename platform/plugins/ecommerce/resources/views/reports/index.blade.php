@extends('core/base::layouts.master')

@section('content')

    <div class="row">
        <div class="col-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-purple-soft">
                            <span data-counter="counterup" data-value="{{ $count['customers'] }}">0</span>
                        </h3>
                        <small>Registered Users</small>
                    </div>
                    <div class="icon">
                        <i class="icon-user"></i>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-6">
            {{-- @include('plugins/ecommerce::reports.partials.count-customers') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-red-haze">
                            <span data-counter="counterup" data-value="{{ $count['products'] }}">0</span>
                        </h3>
                        <small>Talents</small>
                    </div>
                    <div class="icon">
                        <i class="fab fa-product-hunt"></i>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-2  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{ format_price($count['revenue'], true) }}">0</span>
                        </h3>
                        <small>Revenue</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{ format_price($count['revenueMonth'], true) }}">0</span>
                        </h3>
                        <small>Revenue (Month)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{ format_price($count['revenueToday'], true) }}">0</span>
                        </h3>
                        <small>Revenue (Today)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-2  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{ format_price($count['refunds'], true) }}">0</span>
                        </h3>
                        <small>Refunds</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{ format_price($count['refundsMonth'], true) }}">0</span>
                        </h3>
                        <small>Refunds (Month)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-4">
            {{-- @include('plugins/ecommerce::reports.partials.count-sell') --}}
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{ format_price($count['refundsToday'], true) }}">0</span>
                        </h3>
                        <small>Refunds (Today)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>




        <div class="col-lg-2  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{ $count['orders'] }}">0</span>
                        </h3>
                        <small>Total Orders</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{ $count['incompleteOrders'] }}">0</span>
                        </h3>
                        <small>Incomplete Orders</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{ $count['ordersCount']['accepted'] }}">0</span>
                        </h3>
                        <small>Accepted Orders</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{ $count['ordersCount']['rejected'] }}">0</span>
                        </h3>
                        <small>Rejected Orders</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{ $count['ordersCount']['pending'] }}">0</span>
                        </h3>
                        <small>Pending Orders</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{ $count['ordersCount']['cancelled'] }}">0</span>
                        </h3>
                        <small>Cancelled Orders</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 widget_item" id="revenue-report" data-url="{{ route('ecommerce.report.revenue') }}">
            <div class="portlet light bordered portlet-no-padding">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark">{{ trans('plugins/ecommerce::reports.revenue_statistics')  }}</span>
                    </div>
                    @include('plugins/ecommerce::reports.tools')
                </div>
                <div class="row portlet-body widget-content" style="padding: 15px !important;">

                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 widget_item" id="top-selling-products-report" data-url="{{ route('ecommerce.report.top-selling-products') }}">
            <div class="portlet light bordered portlet-no-padding">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark">Top Talents for this month</span>
                    </div>
                    @include('plugins/ecommerce::reports.tools')
                </div>
                <div class="row portlet-body widget-content equal-height" style="padding: 15px 30px !important;">
                    {!! $topSellingProducts->renderTable() !!}
                </div>
            </div>
        </div>
    </div>
@stop
