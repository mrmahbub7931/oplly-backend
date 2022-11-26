<?php

namespace Canopy\Ecommerce\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Canopy\Base\Models\BaseModel;
use Canopy\Base\Supports\Helper;

class NotifyWhenBack extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'ec_talent_notify_when_back';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'talent_id',
        'was_notify_sent',
        'email',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function talent()
    {
        return $this->belongsTo(Talent::class, 'talent_id', 'id');
    }
}
