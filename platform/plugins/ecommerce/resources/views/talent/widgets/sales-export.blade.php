<div class="row">
    <div class="col">
        <p><strong>Export Sales Report</strong></p>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="export_sales_form">
            <div class="row justify-content-center align-items-center">

                <div class="col-5">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                </div>
                <div class="col-1 offset-1">
                    {{-- <button class="btn btn-info btn-sm" type="button" onclick="salesExportReport()">Export</button> --}}
                    <a href="javascript:;" class="btn btn-info btn-sm" onclick="salesExportReport()">Export</a>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="sales_export_url" value="{{ route('talent.sales-export') }}">
<input type="hidden" id="talent_id" value="{{ $talent_id }}">

<script type="text/javascript">
    function salesExportReport()
    {

        var url = $('#sales_export_url').val(),
            start_date = $('#start_date').val(),
            end_date = $('#end_date').val(),
            talent_id = $('#talent_id').val();

        var request_url = url + '?talent_id='+talent_id+'&start_date='+start_date+'&end_date='+end_date;
        window.location = request_url;
        
    }
</script>