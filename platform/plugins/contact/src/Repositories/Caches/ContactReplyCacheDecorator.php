<?php

namespace Canopy\Contact\Repositories\Caches;

use Canopy\Contact\Repositories\Interfaces\ContactReplyInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class ContactReplyCacheDecorator extends CacheAbstractDecorator implements ContactReplyInterface
{
}
