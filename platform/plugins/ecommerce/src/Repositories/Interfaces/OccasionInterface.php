<?php

namespace Canopy\Ecommerce\Repositories\Interfaces;

use Canopy\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface OccasionInterface extends RepositoryInterface
{

    /**
     * get Occasions filter by $param.
     *
     * @param array $param
     * $param['active'] => [true,false]
     * $param['order_by'] => [ASC, DESC]
     * $param['num'] => [int,null]
     * @return Collection Occasions model
     */
    public function getOccasions(array $param);

    /**
     * @return mixed
     */
    public function getDataSiteMap();

    /**
     * @param bool $active
     * @return mixed
     */
    public function getAllOccasions($active = true);
}
