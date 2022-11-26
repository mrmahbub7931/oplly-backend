<?php

namespace Canopy\Ecommerce\Models;

use Canopy\Base\Models\BaseModel;
use Canopy\Base\Supports\Avatar;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use RvMedia;

class Talent extends BaseModel
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 'ec_talent';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'bio',
        'phone',
        // 'password',
        'gender',
        'photo',
        'video',
        'price',
        'discount_percentage',
        'discount_price',
        'business_price',
        'email',
        //'verify_video',
        'main_product_id',
        'cause_details',
        'is_featured',
        'allows_chat',
        'has_cause',
        'customer_id',
        'live_product_id',
        'is_searchable',
        'status',
        'channel',
        'followers',
        'customer_notes',
        'dob',
        'handle',
        'cause_start_date',
        'cause_end_date',
        'allow_discount',
        'allow_business',
        'allow_speed_service',
        'allow_live',
        'live_price',
        'hidden_profile',
        'bank_account_name',
        'branch_name',
        'bank_name',
        'bank_country',
        'bank_account_no',
        'bank_iban',
        'bank_swift',

    ];


    /**
     * @return string
     */
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? RvMedia::getImageUrl($this->photo, 'thumb') : (string)(new Avatar)->create($this->name)->toBase64();
    }

    public function getVideoUrlAttribute(): string
    {
        return $this->video;
    }

    public function getName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function mainOrders()
    {
        return $this->hasMany(Order::class, 'talent_id', 'id');
    }


    /**
     * @return HasOne
     */
    public function liveProduct(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'live_product_id');
    }

    /**
     * @return HasOne
     */
    public function mainProduct(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'main_product_id');
    }

    /**
     * @return HasMany
     */
    public function liveAvailablility(): HasMany
    {
        return $this->hasMany(BookingAvailability::class, 'talent_id');
    }

    /**
     * @return HasOne
     */
    public function account(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    protected static function boot()
    {
        parent::boot();
    }
}
