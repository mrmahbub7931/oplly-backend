<?php

namespace Canopy\Ecommerce\Forms;

use Canopy\Ecommerce\Models\Order;
use Canopy\Payment\Models\Payment;
use Canopy\Base\Forms\FormAbstract;
use Canopy\Ecommerce\Models\Customer;
use Canopy\Ecommerce\Tables\CustomerTable;
use Canopy\Ecommerce\Http\Requests\CustomerCreateRequest;
use Canopy\Ecommerce\Tables\Reports\CustomerReportsTable;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;

class CustomerForm extends FormAbstract
{

    /**
     * @var OrderInterface
     */
    public $orderRepository;

    /**
     * @var CustomerInterface
     */
    public $customerRepository;

    /**
     * @param OrderInterface $orderRepository
     * @param CustomerInterface $customerRepository
     */
    public function __construct(OrderInterface $orderRepository,CustomerInterface $customerRepository){
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        parent::__construct();
    }

    public function fetchCustomerReport(){
        $this->setupModel(new Customer);
        $data = [];
        $data['orders'] = $this->orderRepository
            ->getModel()
            ->where([
                ['is_finished','=', '1'],
                ['user_id','=',$this->getModel()->id]
            ])
            ->count();
        
        $data['sub_total'] = Order::where('user_id',$this->getModel()->id)->whereNotNull([
            'talent_id','payment_id'
        ])->where([
            ['amount','!=',0],
            ['talent_id','!=',0]
        ])
        ->with(
            [
                'payment' => function ($query) {
                    $query->select('id','status as payment_status', 'amount','currency');
                },
            ],
        )->get();

        $total_amount = collect();
        foreach ($data['sub_total'] as $value) {
            if($value->payment->payment_status == 'completed'){
                $total_amount->push($value->amount);
            }
        }
        $data['total_amount'] = array_sum(json_decode($total_amount,true));
        
        $data['last_login'] = $this->customerRepository
        ->getModel()
        ->where('id',$this->getModel()->id)
        ->pluck('last_login_at')
        ->first();

        return response()->json($data);
    }


    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Customer)
            ->setValidatorClass(CustomerCreateRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'text', [
                'label'      => trans('plugins/ecommerce::customer.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::customer.email_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('phone', 'text', [
                'label'      => trans('plugins/ecommerce::customer.phone'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::customer.phone_placeholder'),
                    'data-counter' => 50,
                ],
            ])
            ->add('is_change_password', 'checkbox', [
                'label'      => trans('plugins/ecommerce::customer.change_password'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'hrv-checkbox',
                ],
                'value'      => 1,
            ])
            ->add('password', 'password', [
                'label'      => trans('plugins/ecommerce::customer.password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel()->id ? ' hidden' : null),
                ],
            ])
            ->add('password_confirmation', 'password', [
                'label'      => trans('plugins/ecommerce::customer.password_confirmation'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel()->id ? ' hidden' : null),
                ],
            ])
            ->add('customer_reports', 'html', [
                'html' => '<div id="customer-report-box">' . view("plugins/ecommerce::customers.widgets.reports", ['report' => $this->fetchCustomerReport()]) . '</div>',
            ])
            ->add('customer_reports_table', 'html', [
                'html' => '<div id="customer-report-table">' . view("plugins/ecommerce::customers.widgets.reports-table", ['customer_id' => $this->getModel()->id]) . '</div>',
            ])
            ->add('customer_history', 'html', [
                'html' => '<div id="customer-history-block">' . view("plugins/ecommerce::customers.widgets.customer-history", ['customer_id' => $this->getModel()->id]) . '</div>',
            ])
            ->add('talent_id', 'number', [
                'label'      => __('Linked Talent ID'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group',
                ],
            ])


            ->setBreakFieldPoint('talent_id');
    }
}
