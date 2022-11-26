<?php

namespace Canopy\Ecommerce\Models;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Models\BaseModel;
use Canopy\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Occasion extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_occasions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'order',
        'status',
        'show_standard',
        'show_business',
        'image',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];


    protected static function boot()
    {
        parent::boot();
    }
}
