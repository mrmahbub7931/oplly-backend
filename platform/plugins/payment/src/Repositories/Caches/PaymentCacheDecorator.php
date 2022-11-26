<?php

namespace Canopy\Payment\Repositories\Caches;

use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;
use Canopy\Payment\Repositories\Interfaces\PaymentInterface;

class PaymentCacheDecorator extends CacheAbstractDecorator implements PaymentInterface
{
}
