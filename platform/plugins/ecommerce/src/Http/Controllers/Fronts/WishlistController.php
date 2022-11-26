<?php

namespace Canopy\Ecommerce\Http\Controllers\Fronts;

use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Models\Product;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Response;
use SeoHelper;
use Theme;

class WishlistController extends Controller
{
    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var WishlistInterface
     */
    protected $wishListRepository;

    /**
     * WishlistController constructor.
     * @param WishlistInterface $wishListRepository
     * @param ProductInterface $productRepository
     */
    public function __construct(WishlistInterface $wishListRepository, ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->wishListRepository = $wishListRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        SeoHelper::setTitle(__('Wishlist'));
        $wishlist = collect([]);
        if (auth('customer')->check()) {
            $wishlist = $this->wishListRepository->advancedGet(
                [
                    'condition' => ['customer_id' => auth('customer')->user()->getAuthIdentifier()],
                    'with'      => ['product'],
                    'paginate'  => [
                        'per_page'      => 10,
                        'current_paged' => (int)$request->input('page'),
                    ],
                ]
            );
        }

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Wishlist'), route('public.wishlist'));

        return Theme::scope(
            'ecommerce.wishlist',
            compact('wishlist'),
            'plugins/ecommerce::themes.wishlist'
        )->render();
    }

    /**
     * @param int $productId
     * @param Request $request
     */
    public function display($productId, Request $request)
    {
        $product = $this->productRepository->findOrFail($productId);
        
        if (is_added_to_wishlist($productId)) {
            return (['count' => $product->likes->count(), 'follow' => True]);
        }

        return (['count' => $product->likes->count(), 'follow' => False]);
    }


    /**
     * @param int $productId
     */
    public function follow($productId)
    {
        $product = $this->productRepository->findOrFail($productId);

        if (!auth('customer')->check()) {
            $duplicates = Cart::instance('wishlist')->search(function ($cartItem) use ($productId) {
                return $cartItem->id == $productId;
            });

            if (!$duplicates->isEmpty()) {
                return (['count' => $product->likes->count(), 'follow' => True]);
            }

            Cart::instance('wishlist')->add($productId, $product->name, 1, $product->front_sale_price)
                ->associate(Product::class);

            return (['count' => $product->likes->count(), 'follow' => False]);
        }

        if (is_added_to_wishlist($productId)) {
            $this->wishListRepository->deleteBy([
                'product_id'  => $productId,
                'customer_id' => auth('customer')->user()->getAuthIdentifier(),
            ]);
            
            return (['count' => $product->likes->count(), 'follow' => False]);
        }
        $this->wishListRepository->createOrUpdate([
            'product_id'  => $productId,
            'customer_id' => auth('customer')->user()->getAuthIdentifier(),
        ]);

        return (['count' => $product->likes->count(), 'follow' => True]);
    }

    /**
     * @param int $productId
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store($productId, BaseHttpResponse $response)
    {
        $product = $this->productRepository->findOrFail($productId);

        if (!auth('customer')->check()) {
            $duplicates = Cart::instance('wishlist')->search(function ($cartItem) use ($productId) {
                return $cartItem->id == $productId;
            });

            if (!$duplicates->isEmpty()) {
                return $response
                    ->setMessage(__(':product is already in your favourites list!', ['product' => $product->name]))
                    ->setError(true);
            }

            Cart::instance('wishlist')->add($productId, $product->name, 1, $product->front_sale_price)
                ->associate(Product::class);

            return $response
                ->setMessage(__('Your have added :product to your favourites!', ['product' => $product->name]))
                ->setData(['count' => Cart::instance('wishlist')->count()]);
        }

        if (is_added_to_wishlist($productId)) {
            return $response
                ->setMessage(__(':product is already in favourites list!', ['product' => $product->name]))
                ->setError(true);
        }
        $this->wishListRepository->createOrUpdate([
            'product_id'  => $productId,
            'customer_id' => auth('customer')->user()->getAuthIdentifier(),
        ]);

        return $response
            ->setMessage(__('Added :product to your favourites!', ['product' => $product->name]))
            ->setData(['count' => auth('customer')->user()->wishlist()->count()]);
    }

    /**
     * @param int $productId
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($productId, BaseHttpResponse $response)
    {
        if (!auth('customer')->check()) {
            Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($productId) {
                if ($cartItem->id == $productId) {
                    Cart::instance('wishlist')->remove($rowId);
                    return true;
                }
                return false;
            });

            return $response
                ->setMessage(__('Removed from to your favourites list successfully!'))
                ->setData(['count' => Cart::instance('wishlist')->count()]);
        }

        $this->wishListRepository->deleteBy([
            'product_id'  => $productId,
            'customer_id' => auth('customer')->user()->getAuthIdentifier(),
        ]);

        return $response
            ->setMessage(__('Removed from favourites list successfully!'))
            ->setData(['count' => auth('customer')->user()->wishlist()->count()]);
    }
}
