<?php

namespace Canopy\Ecommerce\Repositories\Eloquent;

use Canopy\Ecommerce\Repositories\Interfaces\BrandInterface;
use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;

class BrandRepository extends RepositoriesAbstract implements BrandInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $condition = [])
    {
        $data = $this->model
            ->where($condition)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('name', 'ASC')
            ->get();

        $this->resetModel();

        return $data;
    }
}
