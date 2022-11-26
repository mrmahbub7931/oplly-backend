<?php

namespace Canopy\AuditLog\Repositories\Caches;

use Canopy\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

/**
 * @since 16/09/2016 10:55 AM
 */
class AuditLogCacheDecorator extends CacheAbstractDecorator implements AuditLogInterface
{
}
