<?php

namespace Canopy\ShurjoPay\Exceptions;

use Illuminate\Support\Facades\Log;

class ShurjoPayException extends \Exception
{
    /**
     * @param $error
     * @return ShurjoPayException
     */
    public static function missingRequiredFields($error): ShurjoPayException
    {
        Log::error($error);
        return new static(
            'Validation Error: There are missing required fields: ' . implode(', ', array_keys($error))
        );
    }

    /**
     * @param $error
     * @return ShurjoPayException
     */
    public static function invalidResponseDuringCheckoutRequest($error): ShurjoPayException
    {
        Log::error($error);
        return new static(
            'Invalid Response during checkout request'
        );
    }

    /**
     * @param $error
     * @return ShurjoPayException
     */
    public static function invalidTokenRequest($error): ShurjoPayException
    {
        Log::error($error);
        return new static(
            $error
        );
    }

    /**
     * @param $error
     * @return ShurjoPayException
     */
    public static function couldNotCompleteOrder($error): ShurjoPayException
    {
        Log::warning($error);
        return new static(
            'The checkout-complete request was incomplete. Possibility of missing post data. Error Code: ' .
            $error['reason'] . ', Message: ' . $error['message']
        );
    }

    /**
     * @param $error
     * @return ShurjoPayException
     */
    public static function invalidToken($error): ShurjoPayException
    {
        Log::error($error);
        return new static(
            'Invalid Token provided. Error Code: 401, Message: Unauthorised'
        );
    }
}
