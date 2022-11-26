<div class="customer_report_box_wrap">
    <div class="row">
        <div class="col-lg-4  col-sm-6">
            
        <div class="dashboard-stat2 bordered">
            <div class="display">
                <div class="number">
                    <h3 class="font-green-sharp">
                        <span data-counter="counterup" data-value="">{{$report->getData()->orders}}</span>
                    </h3>
                    <small>Total Requests</small>
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
                            <span data-counter="counterup" data-value="">{{format_price($report->getData()->total_amount)}}</span>
                        </h3>
                        <small>Amount Paid To Date</small>
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
                            <span data-counter="counterup" data-value="">{{$report->getData()->last_login != null ? Carbon\Carbon::parse($report->getData()->last_login)->diffForHumans() : 'Not signed in'}}</span>
                        </h3>
                        <small>Last login</small>
                    </div>
                    <div class="icon">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>