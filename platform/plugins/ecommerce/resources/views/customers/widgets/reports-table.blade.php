<div class="customer_report_table_wrap">
    <div class="row">
        <div class="col">
            <table class="table table-bordered yajra-datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Talent Name</th>
                        <th>Amount</th>
                        <th>Is speed service</th>
                        <th>Payment Status</th>
                        <th>Request Status</th>
                        <th>Date of Request</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(function () {
      
      var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customer.get-customer-order-reports', $customer_id) }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'talent', name: 'talent name'},
            {data: 'amount', name: 'amount'},
            {data: 'is_speed_service', name: 'Is_speed_service'},
            {data: 'payment.payment_status', name: 'Payment_Status'},
            {data: 'request_status', name: 'Request_Status'},
            {data: 'created_at', name: 'Date_of_Request'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true
            },
        ]
      });
      
    });
  </script>