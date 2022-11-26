<div class="talent_report_box_wrap">
    <div class="row">
        <div class="col">
            <h3>Reports</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <small>Total Requests</small>
                    </div>
                </div>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                            aria-controls="pills-home" aria-selected="true">Today</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                            aria-controls="pills-profile" aria-selected="false">This Week</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
                            aria-controls="pills-contact" aria-selected="false">Total</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="">{{$reports->getData()->totalRequestToday}}</span>
                                </h3>
                                <small>Total Request (Today)</small>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="">{{$reports->getData()->totalRequestWeek}}</span>
                                </h3>
                                <small>Total Request (Week)</small>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="">{{$reports->getData()->totalRequest}}</span>
                                </h3>
                                <small>Total Request (Month)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <small>Revenue</small>
                    </div>
                </div>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-today" role="tab"
                            aria-controls="pills-home" aria-selected="true">Today</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-week-tab" data-toggle="pill" href="#pills-week" role="tab"
                            aria-controls="pills-week" aria-selected="false">This Week</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-month-tab" data-toggle="pill" href="#pills-month" role="tab"
                            aria-controls="pills-month" aria-selected="false">This month</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-total-tab" data-toggle="pill" href="#pills-total" role="tab"
                            aria-controls="pills-total" aria-selected="false">Total</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-today" role="tabpanel"
                        aria-labelledby="pills-today-tab">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="">{{format_price($reports->getData()->revenueToday)}}</span>
                                </h3>
                                <small>Revenue (Today)</small>
                            </div>
                        </div>        
                    </div>
                    <div class="tab-pane fade" id="pills-week" role="tabpanel" aria-labelledby="pills-week-tab">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="">{{format_price($reports->getData()->revenueWeek)}}</span>
                                </h3>
                                <small>Revenue (Week)</small>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-month" role="tabpanel" aria-labelledby="pills-month-tab">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="">{{format_price($reports->getData()->revenueMonth)}}</span>
                                </h3>
                                <small>Revenue (Month)</small>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-total" role="tabpanel" aria-labelledby="pills-total-tab">
                        <div class="display">
                            <div class="number">
                                <h3 class="font-green-sharp">
                                    <span data-counter="counterup" data-value="">{{format_price($reports->getData()->revenueTotal)}}</span>
                                </h3>
                                <small>Revenue (Total)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4  col-sm-6">
            <div class="dashboard-stat2 bordered">
                {{-- {{ dd($reports) }} --}}
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="">{{ $reports->getData()->completedRequest }}</span>
                        </h3>
                        <small>Total Completed Request</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="">{{ $reports->getData()->pendingRequest }}</span>
                        </h3>
                        <small>Pending Request (normal)</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4  col-sm-6">
            <div class="dashboard-stat2 bordered">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="">{{ $reports->getData()->speedService }}</span>
                        </h3>
                        <small>Pending Premium Requests (speed service)</small>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>