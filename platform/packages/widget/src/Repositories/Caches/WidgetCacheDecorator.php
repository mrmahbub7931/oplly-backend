<?php

namespace Canopy\Widget\Repositories\Caches;

use Canopy\Support\Repositories\Caches\CacheAbstractDecorator;
use Canopy\Widget\Repositories\Interfaces\WidgetInterface;

class WidgetCacheDecorator extends CacheAbstractDecorator implements WidgetInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByTheme($theme)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
