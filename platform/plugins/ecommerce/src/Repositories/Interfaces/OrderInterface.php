<?php

namespace Canopy\Ecommerce\Repositories\Interfaces;

use Canopy\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface OrderInterface extends RepositoryInterface
{
    /**
     * @param string $startDate
     * @param string $endDate
     * @param array $select
     * @return Builder[]|Collection
     */
    public function getRevenueData($startDate, $endDate, $select = ['*']);

    /**
     * @param string $startDate
     * @param string $endDate
     * @return Builder[]|Collection
     */
    public function countRevenueByDateRange($startDate, $endDate);
}
