<?php

namespace Canopy\MobileApp\Models;

use Canopy\Base\Models\BaseModel;

class MobileApp extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_app_settings';

    /**
     * @var array
     */
    protected $fillable = [
        'version',
        'homepage',
        'homepage_talent',
        'allow_push',
        'allow_feed',
        'allow_live',
        'allow_causes',
    ];
}
