<?php

namespace Canopy\Ecommerce\Services\Products;

use Canopy\Ecommerce\Models\Product;
use Canopy\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductRepository;
use Canopy\Ecommerce\Repositories\Eloquent\ProductVariationRepository;
use Canopy\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductVariationInterface;

class CreateProductVariationsService
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductAttributeRepository
     */
    protected $productAttributeRepository;

    /**
     * @var ProductVariationRepository
     */
    protected $productVariationRepository;

    /**
     * CreateProductVariationsService constructor.
     * @param ProductInterface $product
     * @param ProductAttributeInterface $productAttribute
     * @param ProductVariationInterface $productVariation
     */
    public function __construct(
        ProductInterface $product,
        ProductAttributeInterface $productAttribute,
        ProductVariationInterface $productVariation
    ) {
        $this->productRepository = $product;

        $this->productAttributeRepository = $productAttribute;

        $this->productVariationRepository = $productVariation;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function execute(Product $product)
    {
        $attributeSets = $product->productAttributeSets()->allRelatedIds();

        $attributes = $this->productAttributeRepository
            ->getAllWithSelected($product->id)
            ->where('is_selected', '<>', null);

        $data = [];

        foreach ($attributeSets as $attributeSet) {
            $data[] = $attributes
                ->where('attribute_set_id', $attributeSet)
                ->pluck('id')
                ->toArray();
        }

        $variationsInfo = combinations($data);

        $variations = [];
        foreach ($variationsInfo as $value) {
            $result = $this->productVariationRepository->getVariationByAttributesOrCreate($product->id, (array)$value);
            $variations[] = $result['variation'];
        }

        return $variations;
    }
}
