<?php

namespace Canopy\Ecommerce\Repositories\Eloquent;

use Canopy\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order', 'ASC')
            ->get();

        $this->resetModel();

        return $data;
    }
}
