<?php

namespace Canopy\Ecommerce\Enums;

use Canopy\Base\Supports\Enum;
use Html;

/**
 * @method static OrderStatusEnum PENDING()
 * @method static OrderStatusEnum ACCEPTED()
 * @method static OrderStatusEnum REJECTED()
 * @method static OrderStatusEnum CANCELED()
 */
class BookingStatusEnum extends Enum
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';
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
            case self::PENDING:
                return Html::tag('span', self::PENDING()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::ACCEPTED:
                return Html::tag('span', self::ACCEPTED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::CANCELED:
                return Html::tag('span', self::CANCELED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            case self::REJECTED:
                return Html::tag('span', self::REJECTED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
