<?php

namespace Canopy\Ecommerce\Models;

use Canopy\Base\Models\BaseModel;

class Region extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_regions';

    /**
     * @var string
     */
    protected $orderBy = 'created_at';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();
    }

}
