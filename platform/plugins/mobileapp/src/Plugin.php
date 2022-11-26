<?php

namespace Canopy\MobileApp;

use Canopy\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('ec_app_settings');
    }
}
