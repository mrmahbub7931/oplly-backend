@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')

    @php Theme::set('pageName', __('Overview')) @endphp
    <div class="card">
        <div class="card-header">
            <h3>Hi {{ auth('customer')->user()->talent->first_name }}, here is your overview</h3>
        </div>
        <div class="card-body dashboard--stats">
            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-4"><h4>At a Glance</h4>
                    <div class="stats--box col-secondary">

                        <div class="stats--title">Requests </div>
                        <div class="row stats--stats">
                            <div class="col">
                                <div class="stat--value">{{$requests['day']}}</div>
                                <span class="stat--label">today</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$requests['week']}}</div>
                                <span class="stat--label">week</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$requests['month']}}</div>
                                <span class="stat--label">month</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$requests['total']}}</div>
                                <span class="stat--label">To date</span>
                            </div>
                        </div>
                    </div>
                    <div class="stats--box col-secondary">
                        <div class="stats--title">Revenue</div>
                        <div class="row stats--stats">
                            <div class="col">
                                <div class="stat--value">{{$revenue['day']}}</div>
                                <span class="stat--label">today</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$revenue['week']}}</div>
                                <span class="stat--label">week</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$revenue['month']}}</div>
                                <span class="stat--label">month</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$revenue['total']}}</div>
                                <span class="stat--label">To date</span>
                            </div>
                        </div>
                    </div>
                    {{--<div class="stats--box col-tertiary">
                        <div class="stats--title">Reviews</div>
                        <div class="row stats--stats">
                            <div class="col">
                                <div class="stat--value">{{$requests['day']}}</div>
                                <span class="stat--label">today</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$requests['month']}}</div>
                                <span class="stat--label">month</span>
                            </div>
                            <div class="col">
                                <div class="stat--value"><i class="fas fa-star"></i> {{$requests['month']}}</div>
                                <span class="stat--label"></span>
                            </div>
                        </div>
                    </div>--}}
                    <div class="stats--box col-primary">
                        <div class="stats--title">Likes</div>
                        <div class="row stats--stats">
                            <div class="col">
                                <div class="stat--value">{{$likes['day']}}</div>
                                <span class="stat--label">today</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$likes['week']}}</div>
                                <span class="stat--label">week</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$likes['month']}}</div>
                                <span class="stat--label">month</span>
                            </div>
                            <div class="col">
                                <div class="stat--value">{{$likes['total']}}</div>
                                <span class="stat--label">all time</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-5">
                    <h4 class="text-center">Performance <small>(month to date)</small></h4>
                    <canvas id="salesToDate" class="p-4 mb-4"></canvas>
                    <canvas id="requestsBreakdown" class="p-4" height="400px" style="max-height: 330px;"></canvas>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <h4>Active Requests</h4>

                    @if ($ordersTodo)
                        @foreach ($ordersTodo as $order)
                            <div class="list--item--cta">
                                <div class="request--info">
                                    <a href="{{ route('talent.requests.view', $order->id) }}">
                                        {{ $order->user->name }}<br>
                                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                    </a>
                                </div>
                                <div>

                                </div>
                                <div class="request--status">
                                    <a class="btn btn-dark btn-sm"
                                       href="{{ route('customer.orders.view', $order->id) }}">
                                        {!! $order->status->toHtml() !!}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div>
                            <p class="text-center">{{ __('No active requests!') }}</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const ctx = document.getElementById('salesToDate');
        const myChart1 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueData['labels']) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($revenueData['data']) !!},
                    backgroundColor: '#ed00a7',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Include a dollar sign in the ticks
                            callback: function(value, index, ticks) {
                                return '£' + value;
                            }
                        }
                    }
                }
            }
        });
        const ctx2 = document.getElementById('requestsBreakdown');
        const myChart2 = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: [
                    'Accepted',
                    'Pending',
                    'Rejected',
                    'Completed',
                    'Canceled'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: {!! $stats !!},
                    backgroundColor: [
                        '#36c6d3',
                        '#343a40',
                        '#ed00a7',
                        '#01ff4f',
                        '#dc3545'
                    ],
                    borderColor: '#000',
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

    </script>
@endsection
