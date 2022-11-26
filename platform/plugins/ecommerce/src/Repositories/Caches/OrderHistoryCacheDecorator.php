<?php

namespace Canopy\Ecommerce\Repositories\Caches;

use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderHistoryCacheDecorator extends CacheAbstractDecorator implements OrderHistoryInterface
{
}
