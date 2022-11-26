<?php

namespace Canopy\Chart\Providers;

use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class ChartServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('core/chart')
            ->loadAndPublishViews();
    }
}
