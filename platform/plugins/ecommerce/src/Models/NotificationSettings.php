<?php

namespace Canopy\Ecommerce\Models;

use Canopy\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NotificationSettings extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_notify_permissions';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'allow_push',
        'allow_email',
        'allow_marketing',
        'allow_news'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return HasOne
     */
    public function account(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'user_id');
    }
}
