<?php

namespace Canopy\Ecommerce\Repositories\Caches;

use Canopy\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductAttributeCacheDecorator extends CacheAbstractDecorator implements ProductAttributeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllWithSelected($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
