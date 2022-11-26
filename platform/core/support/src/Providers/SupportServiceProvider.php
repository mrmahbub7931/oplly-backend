<?php

namespace Canopy\Support\Providers;

use File;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    public function register()
    {
        File::requireOnce(__DIR__ . '/../../helpers/common.php');
    }
}
