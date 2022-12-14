<?php

namespace Canopy\Ecommerce\Supports;

use Canopy\Ecommerce\Models\Product;
use Canopy\Ecommerce\Repositories\Eloquent\ProductRepository;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Exception;
use Throwable;

class RenderProductSwatchesSupport
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * RenderProductSwatchesSupport constructor.
     * @param ProductInterface $productRepository
     */
    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @param array $params
     * @return string
     * @throws Exception
     * @throws Throwable
     */
    public function render(array $params = [])
    {
        $params = array_merge([
            'selected' => [],
            'view'     => 'plugins/ecommerce::themes.attributes.swatches-renderer',
        ], $params);

        $attributeSets = $this->productRepository->getRelatedProductAttributeSets($this->product);

        $attributes = $this->productRepository->getRelatedProductAttributes($this->product)->sortBy('order');

        $product = $this->product;

        $selected = $params['selected'];

        return view($params['view'], compact('attributeSets', 'attributes', 'product', 'selected'))->render();
    }
}
