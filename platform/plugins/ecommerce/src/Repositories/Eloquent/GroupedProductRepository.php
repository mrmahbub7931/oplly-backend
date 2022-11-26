<?php

namespace Canopy\Ecommerce\Repositories\Eloquent;

use Canopy\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;
use DB;

class GroupedProductRepository extends RepositoriesAbstract implements GroupedProductInterface
{
    /**
     * {@inheritDoc}
     */
    public function getChildren($groupedProductId, array $params)
    {
        $this->model = $this->model
            ->join('ec_products', 'ec_products.id', '=', 'ec_grouped_products.parent_product_id')
            ->whereIn('ec_products.id', [$groupedProductId])
            ->distinct();

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function createGroupedProducts($groupedProductId, array $childItems)
    {
        DB::beginTransaction();

        $this->deleteBy(['parent_product_id' => $groupedProductId]);

        foreach ($childItems as $item) {
            $this->model->create([
                'parent_product_id' => $groupedProductId,
                'product_id'        => $item['id'],
                'fixed_qty'         => isset($item['qty']) & $item['qty'] ?: 1,
            ]);
        }

        DB::commit();

        return true;
    }
}