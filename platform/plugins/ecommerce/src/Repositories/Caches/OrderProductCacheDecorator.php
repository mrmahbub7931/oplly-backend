<?php

namespace Canopy\Ecommerce\Repositories\Caches;

use Canopy\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderProductCacheDecorator extends CacheAbstractDecorator implements OrderProductInterface
{
}
