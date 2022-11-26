<?php

namespace Canopy\Bkash\Exceptions;

use Illuminate\Support\Facades\Log;

class BkashException extends \Exception
{
    /**
     * @param $error
     * @return NagadException
     */
    public static function invalidInitResponse($error): BkashException
    {
        Log::error($error);
        return new static(
            'Invalid checkout-initialize response. Error Code: ' . $error['reason'] .
            ', Message: ' . $error['message']
        );
    }

    /**
     * @param $error
     * @return BkashException
     */
    public static function couldNotDecryptInitResponse($error): BkashException
    {
        Log::error($error);
        return new static(
            'Unable to decrypt checkout-initialize response. Error Code: ' . $error['reason'] .
            ', Message: ' . $error['message']
        );
    }

    /**
     * @param $error
     * @return BkashException
     */
    public static function couldNotCompleteOrder($error): BkashException
    {
        Log::warning($error);
        return new static(
            'The checkout-complete request was incomplete. Possibility of missing post data. Error Code: ' .
            $error['reason'] . ', Message: ' . $error['message']
        );
    }
}
