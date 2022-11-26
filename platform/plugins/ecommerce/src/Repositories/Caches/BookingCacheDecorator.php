<?php

namespace Canopy\Ecommerce\Repositories\Caches;

use Canopy\Ecommerce\Repositories\Interfaces\BookingInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class BookingCacheDecorator extends CacheAbstractDecorator implements BookingInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $condition = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
