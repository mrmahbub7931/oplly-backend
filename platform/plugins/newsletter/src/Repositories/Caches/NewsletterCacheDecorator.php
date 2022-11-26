<?php

namespace Canopy\Newsletter\Repositories\Caches;

use Canopy\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class NewsletterCacheDecorator extends CacheAbstractDecorator implements NewsletterInterface
{
}
