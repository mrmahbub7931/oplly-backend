<?php

namespace Canopy\Ecommerce\Http\Controllers\API;

use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Repositories\Interfaces\NotificationSettingsInterface;
use EmailHandler;
use Canopy\Ecommerce\Http\Requests\CustomerCreateRequest;
use Canopy\Ecommerce\Http\Requests\CustomerEditRequest;
use Canopy\Ecommerce\Http\Resources\CustomerOrderHistoryResource;
use Canopy\Ecommerce\Http\Resources\CustomerResource;
use Canopy\Ecommerce\Http\Resources\FavouritesResource;
use Canopy\Ecommerce\Http\Resources\ListRequestsResource;
use Canopy\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use SlugHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{
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
    protected $notificationRepository;

    /**
     * PublicController constructor.
     *
     * @param CustomerInterface $customerRepository
     * @param ProductInterface $productRepository
     * @param OrderInterface $orderRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     * @param WishlistInterface $wishlistRepository
     * @param SlugInterface $slugRepository
     */
    public function __construct(
        CustomerInterface     $customerRepository,
        ProductInterface      $productRepository,
        OrderInterface        $orderRepository,
        OrderHistoryInterface $orderHistoryRepository,
        WishlistInterface     $wishlistRepository,
        SlugInterface         $slugRepository,
        NotificationSettingsInterface $notificationRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->wishlistRepository = $wishlistRepository;
        $this->slugRepository = $slugRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function index(Request $request, BaseHttpResponse $response)
    {
        return $response
            ->setData(['NOT IMPLEMENTED'])
            ->toApiResponse();
    }

    /**
     * @param                             $id
     * @param Request                     $request
     * @param BaseHttpResponse            $response
     * @return BaseHttpResponse
     */
    public function get($id, Request $request, BaseHttpResponse $response)
    {
        $customer = $this->customerRepository->findOrFail($id);
        if (!$customer) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        return $response
            ->setData(new CustomerResource($customer))
            ->toApiResponse();
    }


    /**
     * @param Request                     $request
     * @param BaseHttpResponse            $response
     * @return BaseHttpResponse
     */
    public function getUser(Request $request, BaseHttpResponse $response)
    {
        $currentUser = auth('api-customer')->user();

        $customer = $this->customerRepository->getModel()
            ->where('id', $currentUser->getAuthIdentifier())
            ->with(['talent', 'notifications'])->first();

        if (!$customer) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        return $response
            ->setData(new CustomerResource($customer))
            ->toApiResponse();
    }


    /**
     * @param CustomerCreateRequest $request
     * @param BaseHttpResponse      $response
     * @return BaseHttpResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Throwable
     */
    public function store(CustomerCreateRequest $request, BaseHttpResponse $response)
    {
        $user = $this->customerRepository->create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('register_confirm')) {
            $mailer->sendUsingTemplate(
                'register_confirm',
                $user->email
            );
        }
        return $response
            ->setData(new CustomerResource($user))
            ->toApiResponse();
    }

    /**
     * @param CustomerEditRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update(Request $request, BaseHttpResponse $response)
    {
        $user = auth('api-customer')->user();
        $data = $request->except('password');
        $customer = $this->customerRepository->createOrUpdate($data, [
            'id' => $user->getAuthIdentifier()
        ]);

        return $response
            ->setData(new CustomerResource($customer))
            ->toApiResponse();
    }

    public function logout()
    {
        $user = auth('api-customer')->user();

        if ($user) {
            $accessToken = $user->token();
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                    'revoked' => true
                ]);
            $accessToken->revoke();
        }
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function updatePassword(Request $request, BaseHttpResponse $response)
    {
        $user = auth('api-customer')->user();
        if ($user->getAuthPassword() != bcrypt($request->input('old_password'))) {
            return $response->setError()->setCode(421)->setMessage('Invalid old password');
        }
        $request->merge(['password' => bcrypt($request->input('password'))]);
        $data = $request->input();
        $customer = $this->customerRepository->createOrUpdate($data, [
            'id' => $user->getAuthIdentifier()
        ]);
        return $response
            ->setData(new CustomerResource($customer))
            ->setMessage('Password Updated Successfully')
            ->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function updateNotifications(Request $request, BaseHttpResponse $response)
    {
        $user = auth('api-customer')->user();
        $request->merge([
            'user_id' => $user->getAuthIdentifier()
        ]);
        $data = $request->input();
        $customer = $this->customerRepository->getModel()
            ->where('id', $user->getAuthIdentifier())
            ->with(['notifications'])->first();

        if (!$customer) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }
        $notificationSettings = $this->notificationRepository->createOrUpdate($data, [
            'id' => $customer->notifications->id ?? 0
        ]);
        return $response
            ->setData($notificationSettings)
            ->setMessage('Details updated successfully')
            ->toApiResponse();
    }


    /**
     * @param \Illuminate\Http\Request                     $request
     * @param \Canopy\Base\Http\Responses\BaseHttpResponse $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot(Request $request, BaseHttpResponse $response)
    {
        $credentials = $request->validate(['email' => 'required|email']);

        Password::sendResetLink($credentials);

        return $response
            ->setData(["message" => 'Reset password link sent on your email'])
            ->toApiResponse();
    }

    /**
     * @param mixed $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getOrderHistory($id, Request $request, BaseHttpResponse $response)
    {
        $orders = $this->orderRepository->getModel()
            ->where('user_id', $id)
            ->where('is_finished', 1)->orderBy('created_at', 'desc')
            ->with('user', 'products.product.owner')
            ->paginate(10);

        return $response
            ->setData(CustomerOrderHistoryResource::collection($orders))
            ->toApiResponse();
    }

    /**
     * @param Request                     $request
     * @param BaseHttpResponse            $response
     * @return BaseHttpResponse|\Response
     */
    public function getFavouritesList(Request $request, BaseHttpResponse $response)
    {
        $user = auth('api-customer')->user();

        $wishlist = $this->wishlistRepository->getModel()
            ->where('customer_id', $user->getAuthIdentifier())
            ->with('product', 'product.owner')
            ->get();

        if (!$wishlist) {
            return $response->setError()->setCode(404)->setMessage('No Requests found');
        }

        return $response
            ->setData(FavouritesResource::collection($wishlist))
            ->toApiResponse();
    }

    /**
     * @param Request                     $request
     * @param BaseHttpResponse            $response
     * @return BaseHttpResponse|\Response
     */
    public function addToFavouritesList(Request $request, BaseHttpResponse $response)
    {
        $user = auth('api-customer')->user();

        $wishlist = $this->wishlistRepository->getModel()
            ->where('customer_id', $user->getAuthIdentifier())
            ->with('product', 'product.owner')
            ->get();

        if (!$wishlist) {
            return $response->setError()->setCode(404)->setMessage('No Requests found');
        }

        return $response
            ->setData(FavouritesResource::collection($wishlist))
            ->toApiResponse();
    }

    /**
     * @param                             $id
     * @param Request                     $request
     * @param BaseHttpResponse            $response
     * @return BaseHttpResponse|\Response
     */
    public function getRequests($id, Request $request, BaseHttpResponse $response)
    {
        $orders = $this->orderRepository->getModel()
            ->where('user_id', $id)
            ->where('is_finished', 1)
            ->orderBy('created_at', 'desc')->get();
        // dd($orders);
        if (!$orders) {
            return $response->setError()->setCode(404)->setMessage('No Requests found');
        }

        return $response
            ->setData(ListRequestsResource::collection($orders))
            ->toApiResponse();
    }
}
