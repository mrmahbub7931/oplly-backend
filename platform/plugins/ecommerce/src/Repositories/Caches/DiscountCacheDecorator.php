<?php

namespace Canopy\Ecommerce\Repositories\Caches;

use Canopy\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class DiscountCacheDecorator extends CacheAbstractDecorator implements DiscountInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAvailablePromotions()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getProductPriceBasedOnPromotion(array $productIds = [], array $productCollections = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
