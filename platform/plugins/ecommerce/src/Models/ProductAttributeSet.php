<?php

namespace Canopy\Ecommerce\Models;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Models\BaseModel;
use Canopy\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttributeSet extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_attribute_sets';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'status',
        'order',
        'display_layout',
        'is_searchable',
        'is_comparable',
        'is_use_in_product_listing',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'attribute_set_id')->orderBy('order', 'ASC');
    }
}
