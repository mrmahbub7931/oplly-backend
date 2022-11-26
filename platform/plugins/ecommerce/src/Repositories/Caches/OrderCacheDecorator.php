<?php

namespace Canopy\Ecommerce\Repositories\Caches;

use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderCacheDecorator extends CacheAbstractDecorator implements OrderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRevenueData($startDate, $endDate, $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getRevenueDataForTalentId($talentId, $startDate, $endDate, $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function countRevenueByDateRange($startDate, $endDate)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function countRevenueByTalentIdDateRange($talentID, ?string $startDate = null, ?string $endDate = null)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function countRequestsByTalentIdDateRange($talentID, ?string $startDate = null, ?string $endDate = null)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function countOrdersByStatusTalentId(string $status, ?int $talentId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
