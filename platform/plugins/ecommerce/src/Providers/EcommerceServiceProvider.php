<?php

namespace Canopy\Ecommerce\Providers;

use Canopy\Base\Supports\Helper;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Canopy\Ecommerce\Facades\CartFacade;
use Canopy\Ecommerce\Facades\EcommerceHelperFacade;
use Canopy\Ecommerce\Facades\OrderHelperFacade;
use Canopy\Ecommerce\Http\Middleware\ForceJsonResponse;
use Canopy\Ecommerce\Http\Middleware\RedirectIfCustomer;
use Canopy\Ecommerce\Http\Middleware\RedirectIfNotAPICustomer;
use Canopy\Ecommerce\Http\Middleware\RedirectIfNotCustomer;
use Canopy\Ecommerce\Http\Middleware\RedirectIfTalent;
use Canopy\Ecommerce\Models\Address;
use Canopy\Ecommerce\Models\Brand;
use Canopy\Ecommerce\Models\Currency;
use Canopy\Ecommerce\Models\Customer;
use Canopy\Ecommerce\Models\NotificationSettings;
use Canopy\Ecommerce\Models\Talent;
use Canopy\Ecommerce\Models\Discount;
use Canopy\Ecommerce\Models\FlashSale;
use Canopy\Ecommerce\Models\GroupedProduct;
use Canopy\Ecommerce\Models\Order;
use Canopy\Ecommerce\Models\OrderAddress;
use Canopy\Ecommerce\Models\OrderHistory;
use Canopy\Ecommerce\Models\OrderProduct;
use Canopy\Ecommerce\Models\Product;
use Canopy\Ecommerce\Models\ProductAttribute;
use Canopy\Ecommerce\Models\ProductAttributeSet;
use Canopy\Ecommerce\Models\ProductCategory;
use Canopy\Ecommerce\Models\Occasion;
use Canopy\Ecommerce\Models\ProductCollection;
use Canopy\Ecommerce\Models\ProductTag;
use Canopy\Ecommerce\Models\ProductVariation;
use Canopy\Ecommerce\Models\ProductVariationItem;
use Canopy\Ecommerce\Models\Region;
use Canopy\Ecommerce\Models\Review;
use Canopy\Ecommerce\Models\Shipment;
use Canopy\Ecommerce\Models\ShipmentHistory;
use Canopy\Ecommerce\Models\Shipping;
use Canopy\Ecommerce\Models\ShippingRule;
use Canopy\Ecommerce\Models\ShippingRuleItem;
use Canopy\Ecommerce\Models\StoreLocator;
use Canopy\Ecommerce\Models\Tax;
use Canopy\Ecommerce\Models\Wishlist;
use Canopy\Ecommerce\Models\Withdrawal;
use Canopy\Ecommerce\Repositories\Caches\AddressCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\BookingCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\BrandCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\CurrencyCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\CustomerCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\NotificationSettingsCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\TalentCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\DiscountCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\FlashSaleCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\GroupedProductCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\OrderAddressCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\OrderCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\OrderHistoryCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\OrderProductCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductAttributeCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductAttributeSetCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductCategoryCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\OccasionCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductCollectionCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductTagCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductVariationCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ProductVariationItemCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\RegionCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ReviewCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ShipmentCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ShipmentHistoryCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ShippingCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ShippingRuleCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\ShippingRuleItemCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\StoreLocatorCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\TaxCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\WishlistCacheDecorator;
use Canopy\Ecommerce\Repositories\Caches\WithdrawalCacheDecorator;
use Canopy\Ecommerce\Repositories\Eloquent\AddressRepository;
use Canopy\Ecommerce\Repositories\Eloquent\BookingRepository;
use Canopy\Ecommerce\Repositories\Eloquent\BrandRepository;
use Canopy\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Canopy\Ecommerce\Repositories\Eloquent\CustomerRepository;
use Canopy\Ecommerce\Repositories\Eloquent\NotificationSettingsRepository;
use Canopy\Ecommerce\Repositories\Eloquent\TalentRepository;
use Canopy\Ecommerce\Repositories\Eloquent\DiscountRepository;
use Canopy\Ecommerce\Repositories\Eloquent\FlashSaleRepository;
use Canopy\Ecommerce\Repositories\Eloquent\GroupedProductRepository;
use Canopy\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Canopy\Ecommerce\Repositories\Eloquent\OrderHistoryRepository;
use Canopy\Ecommerce\Repositories\Eloquent\OrderProductRepository;
use Canopy\Ecommerce\Repositories\Eloquent\OrderRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductAttributeSetRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductCategoryRepository;
use Canopy\Ecommerce\Repositories\Eloquent\OccasionRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductCollectionRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductTagRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductVariationItemRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductVariationRepository;
use Canopy\Ecommerce\Repositories\Eloquent\RegionRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ReviewRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ShipmentHistoryRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ShipmentRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ShippingRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ShippingRuleItemRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ShippingRuleRepository;
use Canopy\Ecommerce\Repositories\Eloquent\StoreLocatorRepository;
use Canopy\Ecommerce\Repositories\Eloquent\TaxRepository;
use Canopy\Ecommerce\Repositories\Eloquent\WishlistRepository;
use Canopy\Ecommerce\Repositories\Eloquent\WithdrawalRepository;
use Canopy\Ecommerce\Repositories\Interfaces\AddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\BookingInterface;
use Canopy\Ecommerce\Repositories\Interfaces\BrandInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Repositories\Interfaces\NotificationSettingsInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Canopy\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Canopy\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Canopy\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Canopy\Ecommerce\Repositories\Interfaces\TaxInterface;
use Canopy\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Canopy\Ecommerce\Repositories\Interfaces\WithdrawalInterface;
use Canopy\Ecommerce\Repositories\Interfaces\RegionInterface;
use EcommerceHelper;
use EmailHandler;
use Event;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use SeoHelper;
use SlugHelper;

class EcommerceServiceProvider extends ServiceProvider
{

    use LoadAndPublishDataTrait;

    public function register()
    {
        config([
            'auth.guards.customer'     => [
                'driver'   => 'session',
                'provider' => 'customers',
            ],
            'auth.guards.api-customer'     => [
                'driver'   => 'passport',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model'  => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table'    => 'ec_customer_password_resets',
                'expire'   => 60,
            ],
        ]);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];
        $router->aliasMiddleware('api-customer', RedirectIfNotAPICustomer::class);
        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
        $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);
        $router->aliasMiddleware('talent', RedirectIfTalent::class);
        $router->aliasMiddleware('force-json', ForceJsonResponse::class);

        $this->app->bind(ProductInterface::class, function () {
            return new ProductCacheDecorator(
                new ProductRepository(new Product)
            );
        });

        $this->app->bind(ProductCategoryInterface::class, function () {
            return new ProductCategoryCacheDecorator(
                new ProductCategoryRepository(new ProductCategory)
            );
        });

        $this->app->bind(OccasionInterface::class, function () {
            return new OccasionCacheDecorator(
                new OccasionRepository(new Occasion)
            );
        });


        $this->app->bind(ProductTagInterface::class, function () {
            return new ProductTagCacheDecorator(
                new ProductTagRepository(new ProductTag)
            );
        });


        $this->app->bind(BrandInterface::class, function () {
            return new BrandCacheDecorator(
                new BrandRepository(new Brand)
            );
        });

        $this->app->bind(ProductCollectionInterface::class, function () {
            return new ProductCollectionCacheDecorator(
                new ProductCollectionRepository(new ProductCollection)
            );
        });

        $this->app->bind(CurrencyInterface::class, function () {
            return new CurrencyCacheDecorator(
                new CurrencyRepository(new Currency)
            );
        });

        $this->app->bind(ProductAttributeSetInterface::class, function () {
            return new ProductAttributeSetCacheDecorator(
                new ProductAttributeSetRepository(new ProductAttributeSet),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductAttributeInterface::class, function () {
            return new ProductAttributeCacheDecorator(
                new ProductAttributeRepository(new ProductAttribute),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationInterface::class, function () {
            return new ProductVariationCacheDecorator(
                new ProductVariationRepository(new ProductVariation),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationItemInterface::class, function () {
            return new ProductVariationItemCacheDecorator(
                new ProductVariationItemRepository(new ProductVariationItem),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(TaxInterface::class, function () {
            return new TaxCacheDecorator(
                new TaxRepository(new Tax)
            );
        });

        $this->app->bind(ReviewInterface::class, function () {
            return new ReviewCacheDecorator(
                new ReviewRepository(new Review)
            );
        });

        $this->app->bind(ShippingInterface::class, function () {
            return new ShippingCacheDecorator(
                new ShippingRepository(new Shipping)
            );
        });

        $this->app->bind(ShippingRuleInterface::class, function () {
            return new ShippingRuleCacheDecorator(
                new ShippingRuleRepository(new ShippingRule)
            );
        });

        $this->app->bind(ShippingRuleItemInterface::class, function () {
            return new ShippingRuleItemCacheDecorator(
                new ShippingRuleItemRepository(new ShippingRuleItem)
            );
        });

        $this->app->bind(ShipmentInterface::class, function () {
            return new ShipmentCacheDecorator(
                new ShipmentRepository(new Shipment)
            );
        });

        $this->app->bind(ShipmentHistoryInterface::class, function () {
            return new ShipmentHistoryCacheDecorator(
                new ShipmentHistoryRepository(new ShipmentHistory)
            );
        });

        $this->app->bind(OrderInterface::class, function () {
            return new OrderCacheDecorator(
                new OrderRepository(new Order)
            );
        });

        $this->app->bind(OrderHistoryInterface::class, function () {
            return new OrderHistoryCacheDecorator(
                new OrderHistoryRepository(new OrderHistory)
            );
        });

        $this->app->bind(OrderProductInterface::class, function () {
            return new OrderProductCacheDecorator(
                new OrderProductRepository(new OrderProduct)
            );
        });

        $this->app->bind(OrderAddressInterface::class, function () {
            return new OrderAddressCacheDecorator(
                new OrderAddressRepository(new OrderAddress)
            );
        });

        $this->app->bind(DiscountInterface::class, function () {
            return new DiscountCacheDecorator(
                new DiscountRepository(new Discount)
            );
        });

        $this->app->bind(WishlistInterface::class, function () {
            return new WishlistCacheDecorator(
                new WishlistRepository(new Wishlist)
            );
        });

        $this->app->bind(AddressInterface::class, function () {
            return new AddressCacheDecorator(
                new AddressRepository(new Address)
            );
        });
        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerCacheDecorator(
                new CustomerRepository(new Customer)
            );
        });

        $this->app->bind(BookingInterface::class, function () {
            return new BookingCacheDecorator(
                new BookingRepository(new Talent)
            );
        });

        $this->app->bind(TalentInterface::class, function () {
            return new TalentCacheDecorator(
                new TalentRepository(new Talent)
            );
        });

        $this->app->bind(NotificationSettingsInterface::class, function () {
            return new NotificationSettingsCacheDecorator(
                new NotificationSettingsRepository(new NotificationSettings)
            );
        });


        $this->app->bind(GroupedProductInterface::class, function () {
            return new GroupedProductCacheDecorator(
                new GroupedProductRepository(new GroupedProduct)
            );
        });

        $this->app->bind(StoreLocatorInterface::class, function () {
            return new StoreLocatorCacheDecorator(
                new StoreLocatorRepository(new StoreLocator)
            );
        });

        $this->app->bind(FlashSaleInterface::class, function () {
            return new FlashSaleCacheDecorator(
                new FlashSaleRepository(new FlashSale)
            );
        });

        $this->app->bind(WithdrawalInterface::class, function () {
            return new WithdrawalCacheDecorator(
                new WithdrawalRepository(new Withdrawal)
            );
        });

        $this->app->bind(RegionInterface::class, function () {
            return new RegionCacheDecorator(
                new RegionRepository(new Region)
            );
        });


        Helper::autoload(__DIR__ . '/../../helpers');

        $loader = AliasLoader::getInstance();
        $loader->alias('Cart', CartFacade::class);
        $loader->alias('OrderHelper', OrderHelperFacade::class);
        $loader->alias('EcommerceHelper', EcommerceHelperFacade::class);

        Event::listen(Logout::class, function () {
            if (config('cart.destroy_on_logout')) {
                $this->app->make(SessionManager::class)->forget('cart');
            }
        });
    }

    public function boot()
    {
        SlugHelper::registerModule(Product::class, 'Products');
        SlugHelper::registerModule(Brand::class, 'Brands');
        SlugHelper::registerModule(ProductCategory::class, 'Product Categories');
        SlugHelper::registerModule(ProductTag::class, 'Product Tags');
        SlugHelper::setPrefix(Product::class, 'products');
        SlugHelper::setPrefix(Brand::class, 'brands');
        SlugHelper::setPrefix(ProductTag::class, 'product-tags');
        SlugHelper::setPrefix(ProductCategory::class, 'product-categories');

        $this->setNamespace('plugins/ecommerce')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes([
                'api',
                'base',
                'tax',
                'review',
                'shipping',
                'order',
                'discount',
                'customer',
                'talent',
                'payment',
                'cart',
                'shipment',
                'wishlist',
                'compare',
            ])
            ->loadAndPublishConfigurations([
                'general',
                'shipping',
                'order',
                'cart',
                'email',
                'review',
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        if (! $this->app->routesAreCached()) {
            Passport::routes();
            Passport::enableImplicitGrant();
        }

        $this->app->register(HookServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce',
                    'priority'    => 8,
                    'parent_id'   => null,
                    'name'        => 'plugins/ecommerce::ecommerce.name',
                    'icon'        => 'fa fa-shopping-cart',
                    'url'         => route('products.index'),
                    'permissions' => ['products.index'],
                ])
                ->registerItem([
                    'id'        => 'cms-plugins-ecommerce-report',
                    'priority'  => 0,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name'      => 'plugins/ecommerce::reports.name',
                    'icon'      => 'far fa-chart-bar',
                    'url'       => route('ecommerce.report.index'),
                ])
                /*                 ->registerItem([
                    'id'          => 'cms-plugins-flash-sale',
                    'priority'    => 0,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::flash-sale.name',
                    'icon'        => 'fa fa-bolt',
                    'url'         => route('flash-sale.index'),
                    'permissions' => ['flash-sale.index'],
                ]) */
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-order',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::order.name',
                    'icon'        => 'fa fa-shopping-bag',
                    'url'         => route('orders.index'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-incomplete-order',
                    'priority'    => 2,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::order.incomplete_order',
                    'icon'        => 'fas fa-shopping-basket',
                    'url'         => route('orders.incomplete-list'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce.product',
                    'priority'    => 3,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::products.name',
                    'icon'        => 'fa fa-camera',
                    'url'         => route('products.index'),
                    'permissions' => ['products.index'],
                ])

                ->registerItem([
                    'id'          => 'cms-plugins-occasions',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::occasions.name',
                    'icon'        => 'fa fa-archive',
                    'url'         => route('occasions.index'),
                    'permissions' => ['occasions.index'],
                ])

                ->registerItem([
                    'id'          => 'cms-plugins-product-categories',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-categories.name',
                    'icon'        => 'fa fa-archive',
                    'url'         => route('product-categories.index'),
                    'permissions' => ['product-categories.index'],
                ])
                /*
                ->registerItem([
                    'id'          => 'cms-plugins-product-tag',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-tag.name',
                    'icon'        => 'fa fa-tag',
                    'url'         => route('product-tag.index'),
                    'permissions' => ['product-tag.index'],
                ]) */
                /* ->registerItem([
                    'id'          => 'cms-plugins-product-attribute',
                    'priority'    => 5,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-attributes.name',
                    'icon'        => 'fas fa-glass-martini',
                    'url'         => route('product-attribute-sets.index'),
                    'permissions' => ['product-attribute-sets.index'],
                ]) */
                /*                 ->registerItem([
                    'id'          => 'cms-plugins-brands',
                    'priority'    => 6,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::brands.name',
                    'icon'        => 'fa fa-registered',
                    'url'         => route('brands.index'),
                    'permissions' => ['brands.index'],
                ]) */
                /*                 ->registerItem([
                    'id'          => 'cms-plugins-product-collections',
                    'priority'    => 7,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-collections.name',
                    'icon'        => 'fa fa-file-excel',
                    'url'         => route('product-collections.index'),
                    'permissions' => ['product-collections.index'],
                ]) */
                ->registerItem([
                    'id'          => 'cms-ecommerce-review',
                    'priority'    => 9,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::review.name',
                    'icon'        => 'fa fa-comments',
                    'url'         => route('reviews.index'),
                    'permissions' => ['reviews.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-shipping-provider',
                    'priority'    => 10,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::shipping.shipping',
                    'icon'        => 'fas fa-shipping-fast',
                    'url'         => route('shipping_methods.index'),
                    'permissions' => ['shipping_methods.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-discount',
                    'priority'    => 11,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::discount.name',
                    'icon'        => 'fa fa-gift',
                    'url'         => route('discounts.index'),
                    'permissions' => ['discounts.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-customer',
                    'priority'    => 13,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::customer.name',
                    'icon'        => 'fa fa-users',
                    'url'         => route('customer.index'),
                    'permissions' => ['customer.index'],
                ])

                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-talent',
                    'priority'    => 13,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::talent.name',
                    'icon'        => 'fa fa-users',
                    'url'         => route('talent.index'),
                    'permissions' => ['talent.index'],
                ])

                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-withdrawals',
                    'priority'    => 13,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'Withdrawals',
                    'icon'        => 'fa fa-credit-card',
                    'url'         => route('withdrawal.index'),
                    'permissions' => ['withdrawal.index'],
                ])

                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-regions',
                    'priority'    => 13,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'Regions',
                    'icon'        => 'fa fa-archive',
                    'url'         => route('region.index'),
                    'permissions' => ['region.index'],
                ])

                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce.settings',
                    'priority'    => 999,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::ecommerce.settings',
                    'icon'        => 'fas fa-cogs',
                    'url'         => route('ecommerce.settings'),
                    'permissions' => ['ecommerce.settings'],
                ]);
            /*
            if (EcommerceHelper::isTaxEnabled()) {
                dashboard_menu()->registerItem([
                    'id'          => 'cms-plugins-ecommerce-tax',
                    'priority'    => 14,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::tax.name',
                    'icon'        => 'fas fa-money-check-alt',
                    'url'         => route('tax.index'),
                    'permissions' => ['tax.index'],
                ]);
            } */

            EmailHandler::addTemplateSettings(ECOMMERCE_MODULE_SCREEN_NAME, config('plugins.ecommerce.email'));
        });

        $this->app->booted(function () {
            SeoHelper::registerModule([
                Product::class,
                Brand::class,
                ProductCategory::class,
                ProductTag::class,
            ]);
        });

        $this->app->register(EventServiceProvider::class);
    }
}
