<?php

namespace Canopy\Ecommerce\Enums;

use Canopy\Base\Supports\Enum;
use Html;

/**
 * @method static OrderStatusEnum AVAILABLE()
 * @method static OrderStatusEnum BUSY()
 * @method static OrderStatusEnum BOOKED()
 * @method static OrderStatusEnum CANCELED()
 */
class BookingAvailabilityStatusEnum extends Enum
{
    public const AVAILABLE = 'available';
    public const BUSY = 'busy';
    public const BOOKED = 'booked';
    public const CANCELED = 'canceled';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::order.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::AVAILABLE:
                return Html::tag('span', self::AVAILABLE()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::BUSY:
                return Html::tag('span', self::BUSY()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            case self::CANCELED:
                return Html::tag('span', self::CANCELED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            case self::BOOKED:
                return Html::tag('span', self::BOOKED()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
