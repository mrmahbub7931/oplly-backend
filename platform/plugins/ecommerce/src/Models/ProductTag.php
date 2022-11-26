<?php

namespace Canopy\Ecommerce\Models;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Models\BaseModel;
use Canopy\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductTag extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_tags';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(Product::class, 'ec_product_tag_product', 'tag_id', 'product_id')
            ->where('is_variation', 0);
    }
}
