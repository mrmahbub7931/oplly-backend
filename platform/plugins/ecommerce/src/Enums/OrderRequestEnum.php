<?php

namespace Canopy\Ecommerce\Enums;

use Canopy\Base\Supports\Enum;
use Html;

/**
 * @method static OrderRequestTypeEnum SOMEONE()
 * @method static OrderRequestTypeEnum MYSELF()
 * @method static OrderRequestTypeEnum COMPANY()
 */
class OrderRequestTypeEnum extends Enum
{
    public const SOMEONE = 'single';
    public const MYSELF = 'self';
    public const COMPANY = 'corporate';


    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::order.requesttypes';
}
