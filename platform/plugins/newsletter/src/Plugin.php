<?php

namespace Canopy\Newsletter;

use Canopy\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('newsletters');
    }
}
