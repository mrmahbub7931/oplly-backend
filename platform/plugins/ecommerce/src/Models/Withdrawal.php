<?php

namespace Canopy\Ecommerce\Models;

use Canopy\Base\Models\BaseModel;
use Canopy\Base\Traits\EnumCastable;
use Canopy\Ecommerce\Enums\WithdrawalStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_withdrawals';

    /**
     * @var string
     */
    protected $orderBy = 'created_at';


    /**
     * @var string
     */
    protected $orderDirection = 'DESC';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'talent_id',
        'amount',
        'currency_id',
        'note',
        'is_confirmed',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => WithdrawalStatusEnum::class,
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

    /**
     * @return BelongsTo
     */
    public function talent()
    {
        return $this->belongsTo(Talent::class, 'talent_id', 'id');
    }
}
