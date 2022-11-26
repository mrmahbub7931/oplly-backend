<?php

namespace Canopy\Ecommerce\Http\Controllers\Talent;

use Assets;
use Exception;
use Throwable;
use EmailHandler;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Exports\TalentSalesReport;
use Canopy\Base\Forms\FormBuilder;
use Canopy\Ecommerce\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Canopy\Ecommerce\Models\Customer;
use Canopy\Ecommerce\Models\Talent;
use Canopy\Ecommerce\Forms\TalentForm;
use Illuminate\Contracts\View\Factory;
use Canopy\Ecommerce\Tables\TalentTable;
use Illuminate\Support\Facades\Password;
use Canopy\Base\Events\CreatedContentEvent;
use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Events\UpdatedContentEvent;
use Illuminate\Auth\Passwords\PasswordBroker;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Http\Requests\TalentEditRequest;
use Canopy\Ecommerce\Http\Requests\TalentCreateRequest;
use Canopy\Ecommerce\Services\Products\StoreProductService;
use Canopy\Ecommerce\Http\Requests\TalentUpdateEmailRequest;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\AddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\BookingInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Http\Requests\AddTalentWhenCreateOrderRequest;

class TalentController extends BaseController
{

    /**
     * @var TalentInterface
     */
    protected $talentRepository;
    protected $productRepository;
    protected $customerRepository;

    /**
     * @var BookingInterface
     */
    protected $bookingRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @param TalentInterface   $talentRepository
     * @param AddressInterface  $addressRepository
     * @param ProductInterface  $productRepository
     * @param BookingInterface  $bookingRepository
     * @param CustomerInterface  $customerRepository
     * @param OrderInterface  $orderRepository
     */
    public function __construct(
        TalentInterface $talentRepository,
        AddressInterface $addressRepository,
        ProductInterface $productRepository,
        BookingInterface $bookingRepository,
        CustomerInterface $customerRepository,
        OrderInterface  $orderRepository
    ) {
        $this->talentRepository = $talentRepository;
        $this->addressRepository = $addressRepository;
        $this->productRepository = $productRepository;
        $this->bookingRepository = $bookingRepository;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param TalentTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(TalentTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::talent.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param TalentTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function getWithdrawals(TalentTable $dataTable)
    {
        page_title()->setTitle('Withdrawals');

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::talent.create'));

        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/talent.js');

        return $formBuilder->create(TalentForm::class)->renderForm();
    }

    /**
     * @param TalentCreateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(TalentCreateRequest $request, StoreProductService $service, BaseHttpResponse $response)
    {
        $request->request->add(['main_product_id' => $request->input('main_product_id', 0)]);
        $talent = $this->talentRepository->createOrUpdate($request->input());

        $request->merge(['password' => bcrypt(time())]);
        $request->merge(['name' => $request['first_name']]);

        $customer = $this->customerRepository->createOrUpdate($request->input());
        $customer->talent()->associate($talent);
        $customer->save();

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_register_confirm')) {
            $mailer->sendUsingTemplate(
                'talent_register_confirm',
                $customer->email
            );
        }

        event(new CreatedContentEvent(TALENT_MODULE_SCREEN_NAME, $request, $talent));

        return $response
            ->setPreviousUrl(route('talent.index'))
            ->setNextUrl(route('talent.edit', $talent->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @return string
     */
    public function edit(int $id, FormBuilder $formBuilder)
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/Talent.js')
        ->addStylesDirectly('vendor/core/core/dashboard/css/dashboard.css');

        $talent = $this->talentRepository->findOrFail($id);
        page_title()->setTitle(trans('plugins/ecommerce::talent.edit', ['name' => $talent->first_name . ' '. $talent->last_name?? '']));

        $talent->password = null;

        return $formBuilder->create(TalentForm::class, ['model' => $talent])->renderForm();
    }

    /**
     * @param int $id
     * @param TalentEditRequest $request
     * @param StoreProductService $service
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function update($id, TalentEditRequest $request, StoreProductService $service, BaseHttpResponse $response)
    {
        $talent = $this->talentRepository->findOrFail($id);
        $data = $request->input();

        if ($talent->main_product_id != 0) {
            $product = $this->productRepository->findOrFail($talent->main_product_id);
        } else {
            $product = $this->productRepository->getModel();
        }

        if ($data['status'] == 'approved') {
            $request->request->add(
                [
                    'name' => $data['first_name'] . ' ' . $data['last_name']
                ]
            );
            $request->request->add(
                [
                    'slug' => str_replace(' ', '-', strtolower($data['first_name'] . ' ' . $data['last_name']))
                ]
            );
            $request->request->add(['status' => 'published']);

            $customer = $this->customerRepository->getModel()->where('talent_id', $talent->id)->first();

            $discount_price = NULL;
            if($data['allow_discount'] == true && $data['discount_percentage'] > 0) {
                $discount_value = $talent->price * ($data['discount_percentage'] / 100);
                $discount_price = $talent->price - $discount_value;
            }
            $request->merge(['discount_price' => $discount_price]);

            if (null === $customer) {
                $request->merge(['password' => bcrypt(time())]);
                $request->merge(['name' => $request['first_name']]);
                $customer = $this->customerRepository->createOrUpdate($request->input());
                $customer->talent()->associate($talent);
                $customer->save();
            }

            $broker = Password::broker('customers');
            $token = $broker->createToken($customer);

            $token2 = $broker->tokenExists($customer, $token);
            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);

            if ($mailer->templateEnabled('talent_approved_confirm') && $talent->status != 'approved') {
                $mailer->addVariable('talent_name');
                $mailer->addVariable('reset_link');

                $mailer->setVariableValues([
                    'talent_name' => $talent->first_name,
                    'reset_link' => route('customer.password.reset.update', ['token' => $token])
                ]);
                $mailer->sendUsingTemplate(
                    'talent_approved_confirm',
                    $talent->email
                );
            }
        } else {
            $request->request->add(['status' => 'draft']);
        }

        $product = $service->execute($request, $product);
        $product->owner()->associate($talent);
        $product->save();
        $request->request->add(['status' => $data['status']]);
        $request->request->add(['main_product_id' => $product->id ?? 0]);

        $talent = $this->talentRepository->createOrUpdate($request->input(), ['id' => $id]);

        event(new UpdatedContentEvent(TALENT_MODULE_SCREEN_NAME, $request, $talent));

        return $response
            ->setPreviousUrl(route('talent.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $talent = $this->talentRepository->findOrFail($id);
            $this->talentRepository->delete($talent);
            event(new DeletedContentEvent(TALENT_MODULE_SCREEN_NAME, $request, $talent));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $talent = $this->talentRepository->findOrFail($id);
            $this->talentRepository->delete($talent);
            event(new DeletedContentEvent(TALENT_MODULE_SCREEN_NAME, $request, $talent));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getListTalentForSelect(BaseHttpResponse $response)
    {
        $talents = $this->talentRepository
            ->allBy([], [], ['id', 'name'])
            ->toArray();

        return $response->setData($talents);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getListTalentForSearch(Request $request, BaseHttpResponse $response)
    {
        $talents = $this->talentRepository
            ->getModel()
            ->where('name', 'LIKE', '%' . $request->input('keyword') . '%')
            ->simplePaginate(5);

        foreach ($talents as &$talent) {
            $talent->avatar_url = (string)$talent->avatar_url;
        }

        return $response->setData($talents);
    }


    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getTalentAddresses($id, BaseHttpResponse $response)
    {
        $addresses = $this->addressRepository->allBy(['talent_id' => $id]);

        return $response->setData($addresses);
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getTalentOrderNumbers($id, BaseHttpResponse $response)
    {
        $talent = $this->talentRepository->findById($id);
        if (!$talent) {
            return $response->setData(0);
        }

        return $response->setData($talent->orders()->count());
    }

    /**
     * @param AddTalentWhenCreateOrderRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postCreateTalentWhenCreatingOrder(
        AddTalentWhenCreateOrderRequest $request,
        BaseHttpResponse $response
    ) {
        $request->merge(['password' => bcrypt(time())]);
        $talent = $this->talentRepository->createOrUpdate($request->input());
        $talent->avatar = (string)$talent->avatar_url;

        event(new CreatedContentEvent(TALENT_MODULE_SCREEN_NAME, $request, $talent));

        $request->merge([
            'talent_id' => $talent->id,
            'is_default'  => true,
        ]);

        $address = $this->addressRepository->createOrUpdate($request->input());

        return $response
            ->setData(compact('address', 'talent'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * get all the reports of individual talent by this method
     * @param Request $request
     * @param $id
     */
    public function getTalentOrderReportsTable(Request $request,$id)
    {

        if ($request->ajax()) {
            $data =
            Order::where('talent_id',$id)->whereNotNull([
                'talent_id','payment_id'
            ])->where([
                ['amount','!=',0],
                ['talent_id','!=',0]
            ])
            ->select('id','user_id','amount','payment_id','is_speed_service','created_at','status as request_status')
            ->with(
                [
                    'payment' => function ($query) {
                        $query->select('id','status as payment_status');
                    },
                    'user' => function ($query) {
                        $query->select('id','name');
                    }
                ],
            )
            ->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('amount', function ($request) {
                    return format_price($request->amount); // human readable format
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d M Y | h:i a'); // human readable format
                })
                ->addColumn('action', function($request){
                    $actionBtn = '<a href="'.route('customer.orders.view',$request->id).'" class="edit btn btn-success btn-sm" target="_blank">View</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * export sales report with date range
     *
     * @param int $id
     * @param Request $request
     */
    public function salesReportExport(Request $request)
    {
        $from_date = Carbon::parse($request->start_date);
        $to_date = Carbon::parse($request->end_date);
        $id = $request->talent_id;

        $talent = $this->talentRepository->findById($id);
        $name = $talent->first_name . $talent->last_name;
        return Excel::download(new TalentSalesReport($from_date,$to_date,$id,$this->orderRepository), ''.$name.'.csv');

    }
}
