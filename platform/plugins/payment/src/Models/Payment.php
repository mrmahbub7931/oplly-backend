<?php

namespace Canopy\Payment\Models;

use Canopy\ACL\Models\User;
use Canopy\Base\Models\BaseModel;
use Canopy\Base\Traits\EnumCastable;
use Canopy\Payment\Enums\PaymentMethodEnum;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Html;

class Payment extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'payment_channel' => PaymentMethodEnum::class,
        'status'          => PaymentStatusEnum::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $time = Html::tag('span', $this->created_at->diffForHumans(), ['class' => 'small italic']);

        return 'You have created a payment #' . $this->charge_id . ' via ' . $this->payment_channel->label() . ' ' . $time .
            ': ' . number_format($this->amount, 2, '.', ',') . $this->currency;
    }
}
