<?php

namespace Canopy\Ecommerce\Repositories\Eloquent;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;

class OccasionRepository extends RepositoriesAbstract implements OccasionInterface
{

    /**
     * {@inheritDoc}
     */
    public function getOccasions(array $param)
    {
        $param = array_merge([
            'active'      => true,
            'order_by'    => 'desc',
            'is_child'    => null,
            'is_featured' => null,
            'num'         => null,
        ], $param);

        $data = $this->model->select('ec_occasions.*');

        if ($param['active']) {
            $data = $data->where('ec_occasions.status', BaseStatusEnum::PUBLISHED);
        }

        $data = $data->orderBy('ec_occasions.order', $param['order_by']);

        if ($param['num'] !== null) {
            $data = $data->limit($param['num']);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        $data = $this->model
            ->where('ec_occasions.status', BaseStatusEnum::PUBLISHED)
            ->select('ec_occasions.*')
            ->orderBy('ec_occasions.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }


    /**
     * {@inheritDoc}
     */
    public function getAllOccasions($active = true)
    {
        $data = $this->model->select('ec_occasions.*');
        if ($active) {
            $data = $data->where(['ec_occasions.status' => BaseStatusEnum::PUBLISHED]);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
