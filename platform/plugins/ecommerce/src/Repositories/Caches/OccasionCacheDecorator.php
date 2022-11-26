<?php

namespace Canopy\Ecommerce\Repositories\Caches;

use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class OccasionCacheDecorator extends CacheAbstractDecorator implements OccasionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOccasions(array $param)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAllOccasions($active = true)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
