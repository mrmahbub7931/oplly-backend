<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TalentSalesReport implements FromCollection, WithHeadings
{

    protected $from_date;
    protected $to_date;
    protected $talend_id;
    protected $orderRepository;
    /**
     * @param $from_date
     * @param $to_date
     * @param $talend_id
     * @param $orderRepository
     */
    public function __construct($from_date,$to_date,$id,$orderRepository) {
            $this->from_date = $from_date;
            $this->to_date = $to_date;
            $this->talend_id = $id;
            $this->orderRepository = $orderRepository;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Date/Time',
            'Customer name',
            'Payment Status',
            'Request Status',
            'Amount',
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = $this->orderRepository
        ->getModel()
        ->where('ec_orders.talent_id',$this->talend_id)
        ->whereBetween('ec_orders.created_at', [$this->from_date, $this->to_date])
        ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
        ->join('ec_customers', 'ec_customers.id', '=', 'ec_orders.user_id')
        ->where(function($q){
            $q->where('payments.status', PaymentStatusEnum::COMPLETED)
                ->orWhere('payments.status', PaymentStatusEnum::PENDING);
        })
        ->select('ec_orders.id','ec_orders.created_at','ec_customers.name','payments.status as payment_status','ec_orders.status as request_status','ec_orders.amount')->get();
        return $data;
    }
}
