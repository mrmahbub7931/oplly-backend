<?php

namespace Canopy\Ecommerce\Forms;

use Canopy\Base\Forms\FormAbstract;
use Canopy\Ecommerce\Models\Talent;
use Canopy\Ecommerce\Enums\OrderStatusEnum;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Ecommerce\Http\Requests\TalentCreateRequest;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;

class TalentForm extends FormAbstract
{

    /**
     * @var OrderInterface
     */
    public $orderRepository;

    /**
     * @param OrderInterface $orderRepository
     */
    public function __construct(OrderInterface $orderRepository){
        $this->orderRepository = $orderRepository;
        parent::__construct();
    }

    public function fetchTalentReport(){
        $this->setupModel(new Talent);
        $data = [];

        $data['completedRequest'] = $this->orderRepository
            ->getModel()
            ->where([
                ['is_finished','=', '1'],
                ['talent_id','=',$this->getModel()->id],
            ])->Where(function($q){
                $q->where('status', OrderStatusEnum::COMPLETED)
                  ->orWhere('status', OrderStatusEnum::RELEASED);
            })
            ->whereHas('payment', function($q){
                $q->where('status',PaymentStatusEnum::COMPLETED);
            })
            ->count();

        $data['pendingRequest'] = $this->orderRepository
            ->getModel()
            ->where([
                ['is_finished','=', '1'],
                ['talent_id','=',$this->getModel()->id],
                ['status', '=', OrderStatusEnum::PENDING]
            ])
            ->whereHas('payment', function($q){
                $q->where('status',PaymentStatusEnum::COMPLETED);
            })
            ->count();
        
        $data['speedService'] = $this->orderRepository
            ->getModel()
            ->where([
                ['is_speed_service','=', '1'],
                ['talent_id','=',$this->getModel()->id],
                ['status', '=', OrderStatusEnum::PENDING]
            ])
            ->whereHas('payment', function($q){
                $q->where('status',PaymentStatusEnum::COMPLETED);
            })
            ->count();

        $data['totalRequestToday'] = $this->orderRepository
            ->getModel()
            ->where([
                ['is_finished','=', '1'],
                ['talent_id','=',$this->getModel()->id]
            ])
            ->whereDate('created_at', '>=', \Carbon\Carbon::today())
            ->whereHas('payment', function($q){
                $q->where('status',PaymentStatusEnum::COMPLETED)->orWhere('status',PaymentStatusEnum::PENDING);
            })
            ->count();

            $date = \Carbon\Carbon::now();

            $data['totalRequestWeek'] = $this->orderRepository
            ->getModel()
            ->where([
                ['is_finished','=', '1'],
                ['talent_id','=',$this->getModel()->id]
            ])
            ->whereDate('created_at', '>=', $date->subDays(7))
            ->whereHas('payment', function($q){
                $q->where('status',PaymentStatusEnum::COMPLETED)->orWhere('status',PaymentStatusEnum::PENDING);
            })
            ->count();
            
            $data['totalRequest'] = $this->orderRepository
            ->getModel()
            ->where([
                ['is_finished','=', '1'],
                ['talent_id','=',$this->getModel()->id]
            ])
            ->whereHas('payment', function($q){
                $q->where('status',PaymentStatusEnum::COMPLETED)->orWhere('status',PaymentStatusEnum::PENDING)->orWhere('status',PaymentStatusEnum::FAILED);
            })
            ->count();

            $data['revenueToday'] = $this->orderRepository
                ->getModel()
                ->where('talent_id', $this->getModel()->id)
                ->whereBetween('ec_orders.created_at', [now()->startOfDay()->toDateString(), now()->toDateString()])
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->where('payments.status', PaymentStatusEnum::COMPLETED)
                ->sum('sub_total');

            $data['revenueWeek'] = $this->orderRepository
                ->getModel()
                ->where('talent_id', $this->getModel()->id)
                ->whereBetween('ec_orders.created_at', [$date->today()->subDays(7)->toDateString(), $date->today()->toDateString()])
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->where('payments.status', PaymentStatusEnum::COMPLETED)
                ->sum('sub_total');

            $data['revenueMonth'] = $this->orderRepository
                ->getModel()
                ->where('talent_id', $this->getModel()->id)
                ->whereBetween('ec_orders.created_at', [$date->startOfMonth()->toDateString(), $date->today()->toDateString()])
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->where('payments.status', PaymentStatusEnum::COMPLETED)
                ->sum('sub_total');

            $data['revenueTotal'] = $this->orderRepository
                ->getModel()
                ->where('talent_id',$this->getModel()->id)
                ->whereHas('payment',function($q){
                    $q->where('status',PaymentStatusEnum::COMPLETED);
                })
                ->sum('sub_total');

        return response()->json($data);
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Talent)
            ->setValidatorClass(TalentCreateRequest::class)
            ->withCustomFields()
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('first_name', 'text', [
                'label'      => trans('plugins/ecommerce::talent.first_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::talent.first_name_placeholder'),
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-6',
                ],
            ])
            ->add('last_name', 'text', [
                'label'      => trans('plugins/ecommerce::talent.last_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::talent.last_name_placeholder'),
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-6',
                ],
            ])

            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('email', 'text', [
                'label'      => trans('plugins/ecommerce::talent.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::talent.email_placeholder')
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])
            ->add('phone', 'text', [
                'label'      => trans('plugins/ecommerce::talent.phone'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => __('Phone number'),
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('dob', 'text', [
                'label'         => __('Date Of birth'),
                'label_attr'    => ['class' => 'control-label required'],
                'attr'          => [
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy/mm/dd',
                ],
                'default_value' => now()->addDay()->format('Y/m/d'),
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])
            ->add('gender', 'customSelect', [ // Change "select" to "customSelect" for better UI
                'label'      => __('Gender'),
                'label_attr' => ['class' => 'control-label required'], // Add class "required" if that is mandatory field
                'choices'    => [
                    'male' => __('Male'),
                    'female' => __('Female'),
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen4', 'html', [
                'html' => '<div class="row">',
            ])


            ->add('title', 'text', [
                'label'      => trans('plugins/ecommerce::talent.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => __('Celebrity'),
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-6',
                ],
            ])
            ->add('handle', 'text', [
                'label'      => trans('plugins/ecommerce::talent.handle'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => __('@celebrity'),
                    'data-counter' => 255,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-3',
                ],
            ])
            ->add('channel', 'customSelect', [ // Change "select" to "customSelect" for better UI
                'label'      => __('Social Media Channel'),
                'label_attr' => ['class' => 'control-label required'], // Add class "required" if that is mandatory field
                'choices'    => [
                    'facebook' => __('Facebook'),
                    'instagram' => __('Instagram'),
                    'twitter' => __('Twitter'),
                    'youtube' => __('Youtube'),
                    'linkedin' => __('Linkedin'),
                    'other' => __('Other'),
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-3',
                ],
            ])

            ->add('bio', 'editor', [
                'label'      => trans('plugins/ecommerce::talent.bio'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('plugins/ecommerce::talent.bio_placeholder'),
                    'data-counter' => 500,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12',
                ],
            ])
            ->add('rowClose4', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpenx3', 'html', [
                'html' => '<div class="row">',
            ])


            ->add('rowClosex3', 'html', [
                'html' => '</div>',
            ])

            ->add('rowSepx', 'html', [
                'html' => '<hr>',
            ])


            ->add('rowOpenx4', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('has_cause', 'onOff', [
                'label'         => __('Has Cause'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group col-md-4 col-12',
                ],
            ])
            ->add('cause_start_date', 'text', [
                'label'         => __('Cause Start Date'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy/mm/dd',
                ],
                'default_value' => now(config('app.timezone'))->format('Y/m/d'),
                'wrapper'    => [
                    'class' => 'form-group col-md-4 col-12',
                ],
            ])
            ->add('cause_end_date', 'text', [
                'label'         => __('Cause End Date'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy/mm/dd',
                ],
                'wrapper'    => [
                    'class' => 'form-group col-md-4 col-12',
                ],
            ])

            /* ->add('cause_details', 'editor', [
                'label'      => __('Cause Details'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 3,
                    'data-counter' => 500,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12',
                ],
            ]) */

            ->add('rowClosex4', 'html', [
                'html' => '</div>',
            ])
            ->add('rowSepx2', 'html', [
                'html' => '<hr>',
            ])

            ->add('rowOpen5', 'html', [
                'html' => '<div class="row">',
            ])

            ->add('photo', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4 col-12',
                ],
            ])

            ->add('video', 'mediaFile', [
                'label'      => trans('plugins/ecommerce::talent.video'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4 col-12',
                ],
            ])


            /* ->add('verify_video', 'mediaFile', [
                'label'      => trans('plugins/ecommerce::talent.video'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-4 col-12',
                ],
            ]) */
            ->add('rowClose5', 'html', [
                'html' => '</div>',
            ])
            ->add('rowSep', 'html', [
                'html' => '<hr><div class="row">',
            ])



            ->add('bank_account_name', 'text', [
                'label'      => 'Bank Account Name',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-4',
                ],
            ])
            ->add('bank_account_no', 'text', [
                'label'      => 'Bank Account No',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-4',
                ],
            ])
            ->add('bank_name', 'text', [
                'label'      => 'Bank Name',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-4',
                ],
            ])
            ->add('branch_name', 'text', [
                'label'      => 'Branch Name',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-4',
                ],
            ])
            ->add('bank_country', 'text', [
                'label'      => 'Bank Country',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-4',
                ],
            ])
            ->add('bank_iban', 'text', [
                'label'      => 'Bank IBAN',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 24,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-4',
                ],
            ])
            ->add('bank_swift', 'text', [
                'label'      => 'Bank SWIFT',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 12,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-12 col-md-4',
                ],
            ])





        ->add('rowSep2', 'html', [
                'html' => '</div><hr>',
            ])
            ->add('customer_notes', 'textarea', [ // you can change "text" to "password", "email", "number" or "textarea"
                'label'      => __('Customer Notes'),
                'label_attr' => ['class' => 'control-label required'], // Add class "required" if that is mandatory field
            ])

            ->add('reports_box', 'html', [ // you can change "text" to "password", "email", "number" or "textarea"
                'html'  =>  '<hr><div class="reports_box_wrap">'.view("plugins/ecommerce::talent.widgets.reports-box",['reports' => $this->fetchTalentReport()]).'</div>'
            ])
            ->add('reports_table', 'html', [ // you can change "text" to "password", "email", "number" or "textarea"
                'html'  =>  '<hr><div class="reports_table_wrap">'.view("plugins/ecommerce::talent.widgets.reports-table",['talent_id' => $this->getModel()->id]).'</div>'
            ])
            ->add('export_sales_data', 'html', [ // you can change "text" to "password", "email", "number" or "textarea"
                'html'  =>  '<hr><div class="sales_data_export">'.view("plugins/ecommerce::talent.widgets.sales-export",['talent_id' => $this->getModel()->id]).'</div>'
            ])
            
            ->add('status', 'customSelect', [ // Change "select" to "customSelect" for better UI
                'label'      => __('Profile Status'),
                'label_attr' => ['class' => 'control-label required'], // Add class "required" if that is mandatory field
                'choices'    => [
                    'created' => __('Created'),
                    'pending' => __('Pending'),
                    'approved' => __('Approved'),
                ],
            ])

            ->add('price', 'number', [
                'label'      => __('Price'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-4',
                ],
            ])

            ->add('allow_discount', 'onOff', [
                'label'         => __('Enabled Discount'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
                'wrapper'    => [
                    'class' => 'form-group col-md-4 col-12',
                ],
            ])

            ->add('discount_percentage', 'number', [
                'label'      => __('Discount'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    $this->getModel()->allow_discount ? '' : 'readonly',
                ],
                'wrapper'    => [
                    'class' => 'form-group col-4',
                ],
            ])

            ->add('talent_discount_price', 'number', [
                'label'      => __('Discount Price'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                   'readonly'
                ],
                'wrapper'    => [
                    'class' => 'form-group col-4',
                ],
                'value' => $this->getModel()->discount_price,
            ])

            ->add('hidden_profile', 'onOff', [
                'label'         => __('Is Offline'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])

            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('is_searchable', 'onOff', [
                'label'         => __('Searchable'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])



            ->add('allow_business', 'onOff', [
                'label'         => __('Allow Business Requests'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('business_price', 'number', [
                'label'      => __('Business Price'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-4',
                ],
            ])


            ->add('allow_speed_service', 'onOff', [
                'label'         => __('Enable 24 Hour Service'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])




            ->setBreakFieldPoint('status');
    }
}
