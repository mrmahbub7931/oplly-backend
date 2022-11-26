<?php

namespace Canopy\Ecommerce\Http\Controllers\Talent;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Charts\TimeChart;
use Canopy\Ecommerce\Enums\OrderStatusEnum;
use Canopy\Ecommerce\Http\Requests\AddressRequest;
use Canopy\Ecommerce\Http\Requests\AvatarRequest;
use Canopy\Ecommerce\Http\Requests\EditAccountRequest;
use Canopy\Ecommerce\Http\Requests\TalentCreateRequest;
use Canopy\Ecommerce\Http\Requests\UpdatePasswordRequest;
use Canopy\Ecommerce\Models\Currency;
use Canopy\Ecommerce\Models\NotifyWhenBack;
use Canopy\Ecommerce\Models\Product;
use Canopy\Ecommerce\Models\Wishlist;
use Canopy\Ecommerce\Models\Talent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Canopy\Ecommerce\Repositories\Interfaces\BookingInterface;
use Canopy\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Canopy\Ecommerce\Repositories\Interfaces\AddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\WithdrawalInterface;
use Canopy\Media\Services\ThumbnailService;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Slug\Repositories\Interfaces\SlugInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use EmailHandler;
use Exception;
use File;
use Illuminate\Validation\ValidationException;
use Image;
use Hash;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response as BaseResponse;
use Illuminate\Routing\Controller;
use OrderHelper;
use Response;
use RvMedia;
use SeoHelper;
use SlugHelper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Theme;
use Throwable;

class PublicController extends Controller
{
    /**
     * @var TalentInterface
     */
    protected $talentRepository;

    /**
     * @var CustomerInterface
     */
    protected $customerRepository;


    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderHistoryRepository;

    /**
     * @var WishlistInterface
     */
    protected $wishlistRepository;

    /**
     * @var BookingInterface
     */
    protected $bookingRepository;

    /**
     * @var SlugInterface
     */
    protected $slugRepository;

    /**
     * @var WithdrawalInterface
     */
    protected $withdrawalRepository;

    /**
     * PublicController constructor.
     *
     * @param TalentInterface $talentRepository
     * @param CustomerInterface $customerRepository
     * @param ProductInterface $productRepository
     * @param AddressInterface $addressRepository
     * @param OrderInterface $orderRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     * @param WishlistInterface $wislistRepository
     * @param BookingInterface $bookingRepository
     * @param SlugInterface $slugRepository
     * @param WithdrawalInterface $withdrawalRepository
     */
    public function __construct(
        TalentInterface $talentRepository,
        CustomerInterface $customerRepository,
        ProductInterface $productRepository,
        AddressInterface $addressRepository,
        OrderInterface $orderRepository,
        OrderHistoryInterface $orderHistoryRepository,
        WishlistInterface $wishlistRepository,
        BookingInterface $bookingRepository,
        SlugInterface $slugRepository,
        WithdrawalInterface $withdrawalRepository
    ) {
        $this->talentRepository = $talentRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->addressRepository = $addressRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->wishlistRepository = $wishlistRepository;
        $this->bookingRepository = $bookingRepository;
        $this->slugRepository = $slugRepository;
        $this->withdrawalRepository = $withdrawalRepository;

        //Theme::asset()->add('talent-style', 'vendor/core/plugins/ecommerce/css/talent.css');
        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery']);
    }

    /**
     * @return BaseResponse
     */
    public function getOverview(): BaseResponse
    {
        SeoHelper::setTitle(auth('customer')->user()->name);

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add(__('Overview'), route('talent.overview'));

        $orders = $this->orderRepository->getModel()
            ->where('talent_id', auth('customer')->user()->talent->id)
            ->where('is_finished', 1)
            ->whereIn('status', [
                OrderStatusEnum::PENDING,
                OrderStatusEnum::ACCEPTED,
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('status', 'desc');

        if (auth('customer')->user()->talent->id) {
            $talent = auth('customer')->user()->talent;

            $count = [];
            $count['revenue']['total'] = format_price(
                $this->orderRepository->countRevenueByTalentIdDateRange($talent->id)
            );

            $count['revenue']['month'] = format_price(
                $this->orderRepository->countRevenueByTalentIdDateRange(
                    $talent->id,
                    Carbon::now()->startOfMonth()->toDateString(),
                    Carbon::now()->toDateString()
                )
            );
            $count['revenue']['week'] = format_price(
                $this->orderRepository->countRevenueByTalentIdDateRange(
                    $talent->id,
                    Carbon::now()->startOfWeek()->toDateString(),
                    Carbon::now()->toDateString()
                )
            );
            $count['revenue']['day'] = format_price(
                $this->orderRepository->countRevenueByTalentIdDateRange(
                    $talent->id,
                    Carbon::now()->startOfDay(),
                    Carbon::now()->endOfDay()
                )
            );

            $count['orders']['total'] = $this->orderRepository->countRequestsByTalentIdDateRange($talent->id);
            $count['orders']['day'] = $this->orderRepository->countRequestsByTalentIdDateRange(
                $talent->id,
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            );
            $count['orders']['week'] = $this->orderRepository->countRequestsByTalentIdDateRange(
                $talent->id,
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfDay()
            );
            $count['orders']['month'] = $this->orderRepository->countRequestsByTalentIdDateRange(
                $talent->id,
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfDay()
            );

            $count['ordersStats'] = [
                $this->orderRepository->countOrdersByStatusTalentId(
                    OrderStatusEnum::ACCEPTED,
                    $talent->id
                ),
                $this->orderRepository->countOrdersByStatusTalentId(
                    OrderStatusEnum::PENDING,
                    $talent->id
                ),
                $this->orderRepository->countOrdersByStatusTalentId(
                    OrderStatusEnum::REJECTED,
                    $talent->id
                ),
                $this->orderRepository->countOrdersByStatusTalentId(
                    OrderStatusEnum::COMPLETED,
                    $talent->id
                ),
                $this->orderRepository->countOrdersByStatusTalentId(
                    OrderStatusEnum::CANCELED,
                    $talent->id
                ),

            ];

            $chartTime = [];
            $revenueStats = $this->orderRepository->getRevenueDataForTalentId(
                $talent->id,
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            )->toArray();
            $chartTime['labels'] = [];
            $chartTime['data'] = [];
            foreach ($revenueStats as $data) {
                $chartTime['labels'][] = $data['date'];
                $chartTime['data'][] = $data['revenue'];
            }

            $statsLikesDay = Wishlist::where('product_id', $talent->main_product_id)
                ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->count();
            $statsLikesWeek = Wishlist::where('product_id', $talent->main_product_id)
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $statsLikesMonth = Wishlist::where('product_id', $talent->main_product_id)
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
            $statsLikes = Wishlist::where('product_id', $talent->main_product_id)->count();

            return Theme::scope('ecommerce.talent.overview', [
                'likes' => [
                    'total' => $statsLikes,
                    'month' => $statsLikesMonth,
                    'week' => $statsLikesWeek,
                    'day' => $statsLikesDay
                ],
                'revenue' => $count['revenue'],
                'requests' => $count['orders'],
                'stats' => json_encode($count['ordersStats']),
                'ordersTodo' => $orders->get(),
                'revenueData' => $chartTime
            ], 'plugins/ecommerce::themes.talent.overview')
                ->render();
        }

        return Theme::scope('ecommerce.talent.overview', [], 'plugins/ecommerce::themes.talent.overview')
            ->render();
    }

    /**
     * @return BaseResponse
     */
    public function getSignup(): BaseResponse
    {
        SeoHelper::setTitle('Talent Signup');

        return Theme::scope(
            'ecommerce.talent.signup',
            [],
            'plugins/ecommerce::themes.customers.talent_signup'
        )->render();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     * @throws ValidationException
     */
    public function postSignup(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $isLoggedIn = auth('customer')->user();

        Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email|max:255|unique:ec_customers|unique:ec_talent',
            'phone'    => 'required|digits_between:4,14|numeric',
            'channel'    => 'required',
            'handle'       => 'required'
        ])->validate();

        // create user and talent records
        $talent = $this->talentRepository->createOrUpdate($request->input());
        $request->merge(['password' => bcrypt(time())]);
        $request->merge(['name' => $request['first_name']]);

        $customer = $this->customerRepository->createOrUpdate($request->input());
        $customer->talent()->associate($talent);
        $customer->save();
        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);

        $route = '/talent-signup-success';
        $emailTemplate = 'talent_register_confirm';
        if ($isLoggedIn && $isLoggedIn->talent) {
            $route = '/talent-refer-success';
            $emailTemplate = 'talent_refer_confirm';
        }

        if ($mailer->templateEnabled($emailTemplate)) {
            $mailer->sendUsingTemplate(
                $emailTemplate,
                $customer->email
            );
        }
        return $response
            ->setNextUrl(url($route));
    }

    /**
     * @return BaseResponse
     */
    public function getEditAccount(): BaseResponse
    {
        SeoHelper::setTitle(__('Edit Account'));
        Theme::asset()
            ->add(
                'datepicker-style',
                'vendor/core/core/base/libraries/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ['bootstrap']
            );
        Theme::asset()
            ->container('footer')
            ->add(
                'datepicker-js',
                'vendor/core/core/base/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ['jquery']
            );

        Theme::asset()
            ->container('footer')
            ->add('select2', 'vendor/core/core/base/libraries/select2/js/select2.min.js', ['jquery']);

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Talent Details'), route('talent.edit-account'));

        return Theme::scope('ecommerce.customers.edit-talent-account', [], 'plugins/ecommerce::themes.customers.edit-talent-account')
            ->render();
    }

    /**
     * @return BaseResponse
     */
    public function getViewBookings(): BaseResponse
    {
        SeoHelper::setTitle(__('My Live Bookings'));

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Talent Details'), route('talent.bookings'));

        return Theme::scope('ecommerce.customers.book-live.list', [], 'plugins/ecommerce::themes.customers.book-live.list')
            ->render();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getBookingAvailabilitySlots(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $input = $request->input();
        $startDate = (isset($input['start_date'])) ? Carbon::parse($input['start_date'])->format('Y-m-d') : Carbon::now()->startOfDay()->format('Y-m-d');
        $endDate = (isset($input['end_date'])) ? Carbon::parse($input['end_date'])->format('Y-m-d') : Carbon::now()->addDays(7)->endOfDay()->format('Y-m-d');
        $period = CarbonPeriod::create($startDate, $endDate);
        $day = [];
        $data = [];
        // Iterate over the period
        foreach ($period as $date) {
            $day['date'] = $date->format('Y-m-d');
            $timeSlots = CarbonPeriod::since('09:00')->minutes(30)->until('17:00')->toArray();
            foreach ($timeSlots as $slot) {
                $day['slots'] = [
                    'date' => $slot->toDateTimeString()
                ];
            }
            $data[] = $day;
        }

        return $response->setData($data)->toApiResponse();
    }

    /**
     * @return BaseResponse
     */
    public function getChangeAvailability(): BaseResponse
    {
        SeoHelper::setTitle(__('My Availability'));

        Theme::breadcrumb()->add(__('Home'), url('/'))
            ->add(__('Talent Details'), route('talent.change-availability'));

        return Theme::scope(
            'ecommerce.customers.book-live.settings',
            [],
            'plugins/ecommerce::themes.customers.book-live.settings'
        )->render();
    }

    /**
     * @param EditAccountRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function postEditTalentAccount(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $input = $request->input();

        if ($request->hasFile('photo')) {
            $result = RvMedia::handleUpload($request->file('photo'), 0, 'talent');

            if ($result['error'] != false) {
                return $response->setError()->setMessage($result['message']);
            }
            $photo = $result['data'];
            $input['photo'] = $photo->url;
        }

        if ($request->hasFile('video')) {
            $result2 = RvMedia::handleUpload($request->file('video'), 0, 'talent');

            if ($result2['error'] != false) {
                return $response->setError()->setMessage($result['message']);
            }
            $video = $result2['data'];
            $input['video'] = $video->url;
        }

        $input['allow_speed_service'] = isset($input['allow_speed_service']) ? true : false;
        $input['allow_business'] = isset($input['allow_business']) ? true : false;
        $input['allow_discount'] = isset($input['allow_discount']) ? true : false;
        $input['allow_live'] = isset($input['allow_live']) ? true : false;
        $input['hidden_profile'] = isset($input['hidden_profile']) ? true : false;

        $input['has_cause'] = isset($input['has_cause']) ? true : false;


        if (isset($input['name'])) {
            $name = $input['name'];
            $name = trim($name);
            $input['last_name'] = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $input['first_name'] = trim(preg_replace('#' . preg_quote($input['last_name'], '#') . '#', '', $name));
        }
        $talentRepo = $this->talentRepository->findById(auth('customer')->user()->talent->id);

        if ($talentRepo->hidden_profile == false  && !$input['hidden_profile']) {
            $slug = $this->slugRepository->getFirstBy([
                'reference_id'   => $talentRepo->mainProduct->id,
                'reference_type' => Product::class,
                'prefix'         => SlugHelper::getPrefix(Product::class),
            ]);

            $accountsToNotify = NotifyWhenBack::where('talent_id', auth('customer')->user()->talent->id)->get();
            foreach ($accountsToNotify as $notifyAccount) {
                if ($notifyAccount->was_notify_sent === 0) {
                    $email = $notifyAccount->email ?? $notifyAccount->user->email;
                    $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
                    if ($mailer->templateEnabled('notify_when_back')) {
                        $mailer->setVariableValues([
                            'talent_slug' => $slug->key,
                            'talent_name' => $talentRepo->name,
                            'customer_name' => $notifyAccount->user->name ?? ''
                        ]);
                        $mailer->sendUsingTemplate(
                            'notify_when_back',
                            $email
                        );
                    }
                    $notifyAccount->was_notify_sent = 1;
                    $notifyAccount->save();
                }
            }
        }

        if($input['allow_discount'] == true && $input['discount_percentage'] > 0){
            $price = $talentRepo->price;
            $discount_percentage = $input['discount_percentage'];
            $discount_value = $price * ($discount_percentage/100);
            $discount_price = $price - $discount_value;
            $id = auth('customer')->user()->talent->id;
            Talent::where('id', $id)->update(array('discount_price' => $discount_price));
        }else{
            $discount_price = NULL;
            $id = auth('customer')->user()->talent->id;
            Talent::where('id', $id)->update(array('discount_price' => $discount_price));
        }

        $this->talentRepository->createOrUpdate(
            $input,
            [
                'id' => auth('customer')->user()->talent->id,
            ]
        );

        return $response
            ->setNextUrl(route('talent.edit-account'))
            ->setMessage(__('Your details were updated successfully!'));
    }


    /**
     * @return \Canopy\Theme\Facades\Response|Response|BaseResponse
     */
    public function getEditBankDetails(): BaseResponse
    {
        SeoHelper::setTitle(__('Edit Bank Details'));
        Theme::asset()
            ->add(
                'datepicker-style',
                'vendor/core/core/base/libraries/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ['bootstrap']
            );
        Theme::asset()
            ->container('footer')
            ->add(
                'datepicker-js',
                'vendor/core/core/base/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ['jquery']
            );

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Talent Bank Details'), route('talent.edit-bank-details'));

        return Theme::scope('ecommerce.customers.edit-bank-details', [], 'plugins/ecommerce::themes.customers.edit-bank-details')
            ->render();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEditBankDetails(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $input = $request->input();

        $this->talentRepository->createOrUpdate(
            $input,
            [
                'id' => auth('customer')->user()->talent->id,
            ]
        );

        return $response
            ->setNextUrl(route('talent.edit-bank-details'))
            ->setMessage(__('Your details were updated successfully!'));
    }

    /**
     * @param int $id
     * @param EditAccountRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postUpdateRequest(int $id, Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $input = $request->input();

        if ($request->hasFile('video')) {
            $result = RvMedia::handleUpload($request->file('video'), 0, 'orders', true);

            if ($result['error'] != false) {
                return $response->setError()->setMessage($result['message']);
            }
            $video = $result['data'];
            $input['video'] = $video->url;
        }

        $this->orderRepository->createOrUpdate(
            $input,
            [
                'id' => $id,
            ]
        );

        return $response
            ->setMessage(__('Your details were updated successfully!'));
    }

    /**
     * @param Request $request
     * @return BaseResponse
     */
    public function getTransactionHistory(Request $request): BaseResponse
    {
        SeoHelper::setTitle(__('Transaction History'));
        // dd([$request, auth('customer')->user()->talent->id]);

        $withdrawals = $this->withdrawalRepository->getModel()
            ->where('talent_id', auth('customer')->user()->talent->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            // dd($withdrawals);

        /*$orders = $this->orderRepository->advancedGet([
            'condition' => [
                'talent_id' => auth('customer')->user()->talent->id,
                'is_finished' => 1,
             ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page'),
            ],
        ]);*/

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Transaction History'), route('talent.orders'));

        return Theme::scope(
            'ecommerce.customers.withdrawal-requests.list',
            compact('withdrawals'),
            'plugins/ecommerce::themes.customers.withdrawal-requests.list'
        )->render();
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse|JsonResponse
     */
    public function postUploadRequestVideo($id, Request $request)
    {
        $input = $request->input();

        if ($request->hasFile('video')) {
            $result = RvMedia::handleUpload($request->file('video'), 0, 'orders', true);
            if ($result['error'] != false) {
                return response()->json($result['message'], 422);
            }
            $video = $result['data'];
            $input['video'] = $video->url;
        }

        $this->orderRepository->createOrUpdate(
            $input,
            [
                'id' => $id,
            ]
        );

        return response()->json([
            'video'   =>  RvMedia::getImageUrl($input['video'], null, false)], 200);
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getDownloadVideo($id, BaseHttpResponse $response): BaseHttpResponse
    {
        $order = $this->orderRepository->findOrFail($id);
        $videoPath = $order->video;

        if (Storage::exists($videoPath)) {
            $video = Storage::download($videoPath);
            return $video;
        }

        return $response
            ->setError()
            ->setMessage(__('Video file not found'));
    }

    /**
     * @param Request $request
     * @return BaseResponse
     */
    public function getListRequests(Request $request): BaseResponse
    {
        SeoHelper::setTitle(__('Customer Requests'));
        // dd([$request, auth('customer')->user()->talent->id]);

        $orders = $this->orderRepository->getModel()
            ->where('talent_id', auth('customer')->user()->talent->id)
            ->where('is_finished', 1)->orderBy('created_at', 'desc')

            ->paginate(10);


        /*$orders = $this->orderRepository->advancedGet([
            'condition' => [
                'talent_id' => auth('customer')->user()->talent->id,
                'is_finished' => 1,
             ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page'),
            ],
        ]);*/

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Customer Requests'), route('talent.orders'));

        return Theme::scope(
            'ecommerce.customers.requests.list',
            compact('orders'),
            'plugins/ecommerce::themes.customers.requests.list'
        )->render();
    }

    /**
     * @param int $id
     * @return BaseResponse
     */
    public function getViewRequest(int $id): BaseResponse
    {
        if (!is_numeric($id)) {
            $id = get_order_id_from_order_code('#' .$id);
        }

        SeoHelper::setTitle(__('Request detail :id', ['id' => get_order_code($id)]));

        $order = $this->orderRepository->getFirstBy(
            [
                'id'      => $id,
                // 'owner_id' => auth('customer')->user()->getAuthIdentifier(),
            ],
            ['ec_orders.*'],
            [
                'address',
                'products',
            ]
        );

        if (!$order) {
            abort(404);
        }

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(
            __('Order detail :id', ['id' => get_order_code($id)]),
            route('talent.requests.view', $id)
        );

        return Theme::scope(
            'ecommerce.customers.requests.view',
            compact('order'),
            'plugins/ecommerce::themes.customers.requests.view'
        )->render();
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function getRejectRequest(int $id, BaseHttpResponse $response): BaseHttpResponse
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $id,
            //'owner_id' => auth('customer')->user()->talent->id,
        ], ['*']);

        if (!$order) {
            abort(404);
        }

        if (!in_array($order->status, [PaymentStatusEnum::PENDING, OrderStatusEnum::PROCESSING])) {
            return $response->setError()
                ->setMessage(trans('plugins/ecommerce::order.talent.messages.cancel_error'));
        }

        $this->orderRepository->createOrUpdate(['status' => OrderStatusEnum::REJECTED], compact('id'));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_reject_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_reject_request',
                $order->user->email ? $order->user->email : $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'reject_request',
            'description' => __(
                'Request was rejected by :talent',
                ['talent' => auth('customer')->user()->talent->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.talent.messages.cancel_success'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function getAcceptRequest(int $id, BaseHttpResponse $response): BaseHttpResponse
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $id,
            //'owner_id' => auth('customer')->user()->talent->id,
        ], ['*']);

        if (!$order) {
            abort(404);
        }

        if (!in_array($order->status, [PaymentStatusEnum::PENDING, OrderStatusEnum::PROCESSING])) {
            return $response->setError()
                ->setMessage(trans('plugins/ecommerce::order.talent.messages.cancel_error'));
        }

        $this->orderRepository->createOrUpdate(['status' => OrderStatusEnum::ACCEPTED], compact('id'));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_accept_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_accept_request',
                $order->user->email ? $order->user->email : $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'accept_request',
            'description' => __(
                'Request was accepted by the talent :talent',
                ['talent' => auth('customer')->user()->talent->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.talent.messages.cancel_success'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function getCancelRequest(int $id, BaseHttpResponse $response): BaseHttpResponse
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $id,
            //'owner_id' => auth('customer')->user()->talent->id,
        ], ['*']);

        if (!$order) {
            abort(404);
        }

        if (!in_array($order->status, [PaymentStatusEnum::PENDING, OrderStatusEnum::PROCESSING])) {
            return $response->setError()
                ->setMessage(trans('plugins/ecommerce::order.talent.messages.cancel_error'));
        }

        $this->orderRepository->createOrUpdate(['status' => OrderStatusEnum::CANCELED], compact('id'));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_cancel_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_accept_request',
                $order->user->email ? $order->user->email : $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'cancel_request',
            'description' => __(
                'Request was cancelled by the customer'
            ),
            'order_id'    => $order->id,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.talent.messages.cancel_success'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getReleaseRequest($id, BaseHttpResponse $response): BaseHttpResponse
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $id,
            //'owner_id' => auth('customer')->user()->talent->id,
        ], ['*']);

        if (!$order) {
            abort(404);
        }

        $this->orderRepository->createOrUpdate(['status' => OrderStatusEnum::RELEASED], compact('id'));

        /* $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_complete_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_complete_request',
                $order->user->email ? $order->user->email : $order->address->email
            );
        } */

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'complete_request',
            'description' => __(
                'Request was fulfilled by :talent',
                ['talent' => auth('customer')->user()->talent->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response->setMessage('Video was released successfully');
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|BinaryFileResponse
     * @throws BindingResolutionException|FileNotFoundException
     */
    public function getPrintOrder(int $id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->user_id != auth('customer')->user()->getAuthIdentifier()) {
            return $response
                ->setError()
                ->setMessage(__('Order is not existed!'));
        }

        $invoice = OrderHelper::generateInvoice($order);

        return response()->make(File::get($invoice), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . File::basename($invoice) . '"',
        ]);
    }
}
