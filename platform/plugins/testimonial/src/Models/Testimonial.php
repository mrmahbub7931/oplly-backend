<?php

namespace Canopy\Testimonial\Models;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Models\BaseModel;
use Canopy\Base\Traits\EnumCastable;

class Testimonial extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'testimonials';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'company',
        'content',
        'image',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
