<?php

namespace Canopy\Ecommerce\Http\Controllers\Bookings;

use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Enums\OrderStatusEnum;
use Canopy\Ecommerce\Http\Requests\AddressRequest;
use Canopy\Ecommerce\Http\Requests\AvatarRequest;
use Canopy\Ecommerce\Http\Requests\EditAccountRequest;
use Canopy\Ecommerce\Http\Requests\UpdatePasswordRequest;
use Canopy\Ecommerce\Repositories\Interfaces\AddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Canopy\Media\Services\ThumbnailService;
use Canopy\Payment\Enums\PaymentStatusEnum;
use EmailHandler;
use Exception;
use File;
use Hash;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OrderHelper;
use Response;
use RvMedia;
use SeoHelper;
use Theme;
use Throwable;

class PublicController extends Controller
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
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @var OrderInterface
     */
    protected $orderRepository;
    protected $occasionRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderHistoryRepository;

    /**
     * PublicController constructor.
     * @param CustomerInterface $customerRepository
     * @param ProductInterface $productRepository
     * @param AddressInterface $addressRepository
     * @param OrderInterface $orderRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     */
    public function __construct(
        CustomerInterface $customerRepository,
        ProductInterface $productRepository,
        AddressInterface $addressRepository,
        OrderInterface $orderRepository,
        OrderHistoryInterface $orderHistoryRepository,
        OccasionInterface $occasionRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->addressRepository = $addressRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->occasionRepository = $occasionRepository;

        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/booking.css');
        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery']);
    }

    /**
     * @return Response
     */
    public function getOverview()
    {
        SeoHelper::setTitle(auth('customer')->user()->name);

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Overview'), route('customer.overview'));

        return Theme::scope('ecommerce.customers.overview', [], 'plugins/ecommerce::themes.customers.overview')
            ->render();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getListOrders(Request $request)
    {
        SeoHelper::setTitle(__('Orders'));

        $orders = $this->orderRepository->advancedGet([
            'condition' => [
                'user_id' => auth('customer')->user()->getAuthIdentifier(),
                'is_finished' => 1
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page'),
            ],
        ]);

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Orders'), route('customer.orders'));

        return Theme::scope(
            'ecommerce.customers.orders.list',
            compact('orders'),
            'plugins/ecommerce::themes.customers.orders.list'
        )->render();
    }

    /**
     * @param int $id
     * @return Response
     */
    public function getViewOrder($id)
    {
        SeoHelper::setTitle(__('Order detail :id', ['id' => get_order_code($id)]));

        $order = $this->orderRepository->getFirstBy(
            [
                'id'      => $id,
                'user_id' => auth('customer')->user()->getAuthIdentifier(),
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
            route('customer.orders.view', $id)
        );

        return Theme::scope(
            'ecommerce.customers.orders.view',
            compact('order'),
            'plugins/ecommerce::themes.customers.orders.view'
        )->render();
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function getCancelOder($id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $id,
            'user_id' => auth('customer')->user()->getAuthIdentifier(),
        ], ['*']);

        if (!$order) {
            abort(404);
        }

        if (!in_array($order->status, [PaymentStatusEnum::PENDING, OrderStatusEnum::PROCESSING])) {
            return $response->setError()
                ->setMessage(trans('plugins/ecommerce::order.customer.messages.cancel_error'));
        }

        $this->orderRepository->createOrUpdate(['status' => OrderStatusEnum::CANCELED], compact('id'));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('customer_cancel_order')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'customer_cancel_order',
                $order->user->email ? $order->user->email : $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'cancel_order',
            'description' => __(
                'Order is cancelled by custom :customer',
                ['customer' => auth('customer')->user()->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.customer.messages.cancel_success'));
    }
}
