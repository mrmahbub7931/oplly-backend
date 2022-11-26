<?php

namespace Canopy\ACL\Repositories\Caches;

use Canopy\ACL\Repositories\Interfaces\RoleInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class RoleCacheDecorator extends CacheAbstractDecorator implements RoleInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
