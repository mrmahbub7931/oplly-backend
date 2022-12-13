<?php

namespace Canopy\Ecommerce\Models;

use Canopy\ACL\Models\User;
use Canopy\Base\Models\BaseModel;
use Canopy\Ecommerce\Models\Talent;
use Canopy\Ecommerce\Models\Customer;

class CustomerTalentHistory extends BaseModel
{
    
    /**
     * @var string
     */
    protected $table = 'ec_customer_talent_history';

    /**
     * @var array
     */
    protected $fillable = [
        'action',
        'description',
        'user_id',
        'customer_id',
        'talent_id',
        'extras',
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
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'order_id', 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function talent()
    {
        return $this->belongsTo(Talent::class, 'order_id', 'id')->withDefault();
    }
}
