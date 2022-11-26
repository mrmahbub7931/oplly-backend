<?php

namespace Canopy\Dashboard\Repositories\Caches;

use Canopy\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;

class DashboardWidgetSettingCacheDecorator extends CacheAbstractDecorator implements DashboardWidgetSettingInterface
{
    /**
     * {@inheritDoc}
     */
    public function getListWidget()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
