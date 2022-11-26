<?php

namespace Canopy\Ecommerce\Http\Controllers\Fronts;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Http\Responses\BaseHttpResponse;

use Canopy\Base\Supports\Helper;
use Canopy\Ecommerce\Enums\OrderStatusEnum;
use Canopy\Ecommerce\Http\Requests\AddressRequest;
use Canopy\Ecommerce\Http\Requests\AvatarRequest;
use Canopy\Ecommerce\Http\Requests\EditAccountRequest;
use Canopy\Ecommerce\Http\Requests\UpdatePasswordRequest;
use Canopy\Ecommerce\Models\Product;
use Canopy\Ecommerce\Repositories\Interfaces\AddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Slug\Repositories\Interfaces\SlugInterface;
use Canopy\Media\Services\ThumbnailService;
use Canopy\Payment\Enums\PaymentStatusEnum;
use EmailHandler;
use Exception;
use File;
use Hash;
use Canopy\SeoHelper\SeoOpenGraph;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OrderHelper;
use Response;
use RvMedia;
use SeoHelper;
use SlugHelper;
use Theme;
use Throwable;

class PublicTalentController extends Controller
{
    /**
     * @var TalentInterface
     */
    protected $talentRepository;

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
     * @var ReviewInterface
     */
    protected $reviewRepository;

    /**
     * @var SlugInterface
     */
    protected $slugRepository;

    /**
     * PublicController constructor.
     * @param TalentInterface $talentRepository
     * @param ProductInterface $productRepository
     * @param AddressInterface $addressRepository
     * @param OrderInterface $orderRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     */
    public function __construct(
        TalentInterface $talentRepository,
        ProductInterface $productRepository,
        AddressInterface $addressRepository,
        SlugInterface $slugRepository
    ) {
        $this->talentRepository = $talentRepository;
        $this->productRepository = $productRepository;
        $this->addressRepository = $addressRepository;
        $this->slugRepository = $slugRepository;

        Theme::asset()
            ->add('talent-style', 'vendor/core/plugins/ecommerce/css/Talent.css');
        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery']);

        Theme::asset()
            ->container('footer')
            ->add('avatar-js', 'vendor/core/plugins/ecommerce/js/avatar.js', ['jquery']);
    }

    /**
     * @return Response
     */
    public function getOverview()
    {
        SeoHelper::setTitle(auth('talent')->user()->name);

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Overview'), route('talent.overview'));

        return Theme::scope('ecommerce.talent.overview', [], 'plugins/ecommerce::themes.talent.overview')
            ->render();
    }



    /**
     * @return Response
     */
    public function getTalent($slug)
    {
        $slug = $this->slugRepository->getFirstBy([
            'key'            => $slug,
            'reference_type' => Product::class,
            'prefix'         => SlugHelper::getPrefix(Product::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $condition = [
            'ec_products.id'     => $slug->reference_id,
            'ec_products.status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && request()->input('preview')) {
            Arr::forget($condition, 'status');
        }

        $product = get_products([
            'condition' => $condition,
            'take'      => 1,
            'with'      => [
                'defaultProductAttributes',
                'slugable',
                'tags',
                'tags.slugable',
            ],
        ]);

        if (!$product) {
            abort(404);
        }

        SeoHelper::setTitle($product->name)->setDescription($product->description);

        $meta = new SeoOpenGraph;
        if ($product->image) {
            $meta->setImage(RvMedia::getImageUrl($product->image));
        }
        $meta->setDescription($product->description);
        $meta->setUrl($product->url);
        $meta->setTitle($product->name);

        SeoHelper::setSeoOpenGraph($meta);

        Helper::handleViewCount($product, 'viewed_product');

        Theme::breadcrumb()->add(__('Home'), url('/'))
            ->add(__('Products'), route('public.products'));

        $category = $product->categories->first();
        if ($category) {
            Theme::breadcrumb()->add($category->name, $category->url);
        }

        Theme::breadcrumb()->add($product->name, $product->url);

        admin_bar()
            ->registerLink(
                trans('plugins/ecommerce::products.edit_this_product'),
                route('products.edit', $product->id)
            );

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $product);

        return Theme::scope('ecommerce.product', compact('product'), 'plugins/ecommerce::themes.product')->render();
    }




    /**
     * @return Response
     */
    public function getEditAccount()
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

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Account Details'), route('talent.edit-account'));

        return Theme::scope('ecommerce.talent.edit-account', [], 'plugins/ecommerce::themes.talent.edit-account')
            ->render();
    }

    /**
     * @param EditAccountRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEditAccount(EditAccountRequest $request, BaseHttpResponse $response)
    {
        $this->talentRepository->createOrUpdate(
            $request->input(),
            [
                'id' => auth('talent')->user()->getAuthIdentifier(),
            ]
        );

        return $response
            ->setNextUrl(route('talent.edit-account'))
            ->setMessage(__('Update profile successfully!'));
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
                'user_id' => auth('talent')->user()->getAuthIdentifier(),
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page'),
            ],
        ]);

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Orders'), route('talent.orders'));

        return Theme::scope(
            'ecommerce.talent.orders.list',
            compact('orders'),
            'plugins/ecommerce::themes.talent.orders.list'
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
                'user_id' => auth('talent')->user()->getAuthIdentifier(),
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
            route('talent.orders.view', $id)
        );

        return Theme::scope(
            'ecommerce.talent.orders.view',
            compact('order'),
            'plugins/ecommerce::themes.talent.orders.view'
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
            'user_id' => auth('talent')->user()->getAuthIdentifier(),
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
        if ($mailer->templateEnabled('talent_cancel_order')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_cancel_order',
                $order->user->email ? $order->user->email : $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'cancel_order',
            'description' => __(
                'Order is cancelled by custom :Talent',
                ['talent' => auth('talent')->user()->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.Talent.messages.cancel_success'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws BindingResolutionException
     */
    public function getPrintOrder($id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->user_id != auth('talent')->user()->getAuthIdentifier()) {
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
