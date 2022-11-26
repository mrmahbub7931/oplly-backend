<?php

namespace Canopy\Ecommerce\Http\Controllers\API;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Events\CreatedContentEvent;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Blog\Repositories\Interfaces\CategoryInterface;
use Canopy\Ecommerce\Enums\OrderStatusEnum;
use Canopy\Ecommerce\Http\Requests\EditAccountRequest;
use Canopy\Ecommerce\Http\Requests\TalentCreateRequest;
use Canopy\Ecommerce\Http\Resources\ListTalentResource;
use Canopy\Ecommerce\Http\Resources\ListTalentSearchResultsResource;
use Canopy\Ecommerce\Http\Resources\CustomerOrderHistoryResource;
use Canopy\Ecommerce\Http\Resources\TalentResource;
use Canopy\Ecommerce\Models\NotifyWhenBack;
use Canopy\Ecommerce\Models\Product;
use Canopy\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Canopy\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Services\Products\GetProductService;
use Canopy\Ecommerce\Services\Products\StoreProductService;
use Canopy\Ecommerce\Tables\Reports\TopSellingProductsTable;
use Canopy\Media\RvMedia;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Slug\Repositories\Interfaces\SlugInterface;
use EmailHandler;
use Illuminate\Http\JsonResponse;
use OrderHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\App;
use SlugHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;

class TalentController extends Controller
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
     * @var SlugInterface
     */
    protected $slugRepository;

    protected $reviewRepository;

    protected $categoryRepository;

    /**
     * PublicController constructor.
     *
     * @param TalentInterface $talentRepository
     * @param CustomerInterface $customerRepository
     * @param ProductInterface $productRepository
     * @param OrderInterface $orderRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     * @param WishlistInterface $wishlistRepository
     * @param SlugInterface $slugRepository
     * @param ReviewInterface $reviewRepository
     */
    public function __construct(
        TalentInterface       $talentRepository,
        CustomerInterface     $customerRepository,
        ProductInterface      $productRepository,
        OrderInterface        $orderRepository,
        OrderHistoryInterface $orderHistoryRepository,
        WishlistInterface     $wishlistRepository,
        SlugInterface         $slugRepository,
        ReviewInterface $reviewRepository
    ) {
        $this->talentRepository = $talentRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->wishlistRepository = $wishlistRepository;
        $this->slugRepository = $slugRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function index(Request $request, BaseHttpResponse $response)
    {
        $where = [
            'is_variation' => 0,
            'status'       => BaseStatusEnum::PUBLISHED,
        ];
        if ($request->input('category') == 'featured') {
            $where['is_featured'] = 1;
        }

        $products = $this->productRepository->getModel()
            ->where($where)->paginate($request->input('per_page', 10));

        if (!$products) {
            return $response->setError()->setCode(404)->setMessage('Not found')->toApiResponse();
        }

        return $response
            ->setData(ListTalentResource::collection($products))
            ->toApiResponse();
    }

    /**
     * @param string           $keyword
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function search(string $keyword, Request $request, BaseHttpResponse $response)
    {
        if ($keyword !== 'category' && $keyword !== '') {
            $request->merge(['q' => $keyword]);
        }

        $category = null;
        if (strpos($keyword, 'category-') !== false) {
            $request->merge(['q' => null]);
            $keyword = explode('-', $keyword);
            $category = $keyword[1] ?? null;
        }

        $productService = App::make(GetProductService::class);

        if ($keyword === 'recommended') {
            $data = get_featured_products();
        } elseif (null !== $category) {
            $data = $productService->getProductsInCategory($request, $category);
        } else {
            $data = $productService->getProduct($request);
        }

        return $response
            ->setData(ListTalentSearchResultsResource::collection($data))
            ->toApiResponse();
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function get($id, Request $request, BaseHttpResponse $response)
    {
        $product = $this->productRepository->getModel()
            ->where('talent_id', $id)
            ->with('owner', 'owner.account', 'owner.account.notifications')
            ->firstOrFail();

        if (!$product) {
            return $response->setError()->setCode(404)->setMessage('Not found')->toApiResponse();
        }

        return $response
            ->setData(new TalentResource($product))
            ->toApiResponse();
    }

    /**
     * @param TalentCreateRequest $request
     * @param BaseHttpResponse    $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function store(TalentCreateRequest $request, BaseHttpResponse $response)
    {
        $request->request->add([
            'main_product_id' => $request->input('main_product_id', 0)
        ]);
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

        return $response->setData($talent)->toApiResponse();
    }

    /**
     * Update Talent User Details
     *
     * This endpoint allows updating details for the talent for the authenticated user who is linked to the talent
     * @authenticated
     *
     * @queryParam name required string Talent Name
     *
     * @param Request $request
     * @param BaseHttpResponse   $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function update(Request $request, BaseHttpResponse $response)
    {
        $talentId = auth('api-customer')->user()->talent->id;
        $input = $request->input();

        $input['allow_speed_service'] = $request->input('allow_speed_service', false);
        $input['allow_business'] = $request->input('allow_business', false);
        $input['allow_discount'] = $request->input('allow_discount', false);
        $input['allow_live'] = $request->input('allow_live', false);
        $input['hidden_profile'] = $request->input('hidden_profile', false);
        $input['has_cause'] = $request->input('has_cause', false);

        if (isset($input['name'])) {
            $name = $input['name'];
            $name = trim($name);
            $input['last_name'] = (strpos($name, ' ') === false)
                ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $input['first_name'] = trim(preg_replace('#' .
                preg_quote($input['last_name'], '#') . '#', '', $name));
        }
        $talentRepo = $this->talentRepository->findById($talentId);

        if ($talentRepo->hidden_profile == false  && !$input['hidden_profile']) {
            $slug = $this->slugRepository->getFirstBy([
                'reference_id'   => $talentRepo->mainProduct->id,
                'reference_type' => Product::class,
                'prefix'         => SlugHelper::getPrefix(Product::class),
            ]);

            $accountsToNotify = NotifyWhenBack::where(
                'talent_id',
                $talentId
            )->get();

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

        $talent = $this->talentRepository->createOrUpdate(
            $input,
            [
                'id' => $talentId,
            ]
        );

        return $response
            ->setMessage('Settings updated successfully')
            ->toApiResponse();
    }


    /**
     * Update Talent Banking Details
     *
     * This endpoint allows updating details for the talent for the authenticated user who is linked to the talent
     * @authenticated
     *
     * @queryParam name required string Talent Name
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function updateBanking(Request $request, BaseHttpResponse $response)
    {
        $talentId = auth('api-customer')->user()->talent->id;
        $input = $request->input();

        $talentRepo = $this->talentRepository->findById($talentId);

        if (!$talentRepo) {
            return $response->setError()->setCode(404)->setMessage('Not found')->toApiResponse();
        }
        $talent = $this->talentRepository->createOrUpdate(
            $input,
            [
                'id' => $talentId,
            ]
        );

        return $response
            ->setData($talent)
            ->setMessage('Details updated successfully')
            ->toApiResponse();
    }

    /**
     * @param int              $id
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getOrderHistory($id, Request $request, BaseHttpResponse $response)
    {
        $orders = $this->orderRepository->getModel()
            ->where('talent_id', $id)
            ->where('is_finished', 1)->orderBy('created_at', 'desc')
            ->with('user', 'products.product.owner')
            ->paginate(10);

        return $response
            ->setData(CustomerOrderHistoryResource::collection($orders))
            ->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getBookingAvailabilitySlots(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $input = $request->input();
        $startDate = (isset($input['start_date'])) ? Carbon::parse($input['start_date'])->format('Y-m-d') :
                                                     Carbon::now()->startOfDay()->format('Y-m-d');
        $endDate = (isset($input['end_date'])) ? Carbon::parse($input['end_date'])->format('Y-m-d') :
                                                 Carbon::now()->addDays(7)->endOfDay()->format('Y-m-d');
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
        return $response
            ->setData($data)
            ->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getBookingAvailability(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $input = $request->input();
        $data = [];
        return $response
            ->setData($data)
            ->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postNotifyWhenBack(Request $request, BaseHttpResponse $response)
    {
        $input = $request->input();
        if (!$request['talent_id']) {
            return $response->setError()->setCode(404)->setMessage('Wrong Talent');
        }

        $notify = new NotifyWhenBack();
        $notify->talent_id = $request['talent_id'];

        if ($request['email']) {
            $notify->email = $request['email'];
        } elseif ($request['user_id']) {
            $notify->user_id = $request['user_id'];
        }

        $notify->save();

        return $response
            ->setMessage('success')
            ->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return JsonResponse
     */
    public function getStats(Request $request, BaseHttpResponse $response): JsonResponse
    {
        $talentId = auth('api-customer')->user()->talent->id;
        $input = $request->input();
        $count = [];

        /*
        $chartData = $this->orderRepository->getRevenueData($startDate, $endDate)->toArray();
        */
        $count = [
            'total' => [
                'label' => 'To date',
                'likes' => 0,
                'reviews' => 0,
                'rating' => 4.9
            ],
            'month' => [
                'label' => 'Month',
            ],
            'week' => [
                'label' => 'Week'
            ],
            'today' => [
                'label' => 'Today'
            ]
        ];
        $count['total']['revenue'] = $this->orderRepository
            ->getModel()
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->where('ec_orders.talent_id', '=', $talentId)
            ->sum('sub_total');

        $count['month']['revenue'] = $this->orderRepository
            ->getModel()
            ->whereBetween('ec_orders.created_at', [now()->startOfMonth()->toDateString(), now()->toDateString()])
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->where('ec_orders.talent_id', '=', $talentId)
            ->sum('sub_total');

        $count['month']['requests'] = $this->orderRepository
            ->getModel()
            ->whereBetween('created_at', [now()->startOfMonth()->toDateString(), now()->toDateString()])
            ->where('talent_id', '=', $talentId)
            ->count();

        $count['week']['requests'] = $this->orderRepository
            ->getModel()
            ->whereBetween('created_at', [now()->startOfWeek()->toDateString(), now()->toDateString()])
            ->where('talent_id', '=', $talentId)
            ->count();

        $count['today']['requests'] = $this->orderRepository
            ->getModel()
            ->whereBetween('created_at', [now()->startOfDay()->toDateString(), now()->toDateString()])
            ->where('talent_id', '=', $talentId)
            ->count();

        $count['ordersStatus']['accepted'] = $this->orderRepository
            ->getModel()
            ->where('status', 'accepted')
            ->where('talent_id', '=', $talentId)
            ->count();

        $count['ordersStatus']['pending'] = $this->orderRepository
            ->getModel()
            ->where('status', 'pending')
            ->where('talent_id', '=', $talentId)
            ->count();

        $count['ordersStatus']['rejected'] = $this->orderRepository
            ->getModel()
            ->where('status', 'rejected')
            ->where('talent_id', '=', $talentId)
            ->count();

        return response()->json($count);
    }
}
