<?php

namespace Canopy\Ecommerce\Repositories\Eloquent;

use Canopy\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;

class ReviewRepository extends RepositoriesAbstract implements ReviewInterface
{
    public function getReviewsRating(int $productId): float
    {
        return $this->model->where('product_id', $productId)->pluck('star')->avg();
    }
}
