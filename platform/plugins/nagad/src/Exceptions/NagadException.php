<?php

namespace Canopy\Nagad\Exceptions;

use Illuminate\Support\Facades\Log;

class NagadException extends \Exception
{
    /**
     * @param $error
     * @return NagadException
     */
    public static function invalidInitResponse($error): NagadException
    {
        Log::error($error);
        return new static(
            'Invalid checkout-initialize response. Error Code: ' . $error['reason'] .
            ', Message: ' . $error['message']
        );
    }

    /**
     * @param $error
     * @return NagadException
     */
    public static function couldNotDecryptInitResponse($error): NagadException
    {
        Log::error($error);
        return new static(
            'Unable to decrypt checkout-initialize response. Error Code: ' . $error['reason'] .
            ', Message: ' . $error['message']
        );
    }

    /**
     * @param $error
     * @return NagadException
     */
    public static function couldNotCompleteOrder($error): NagadException
    {
        Log::warning($error);
        return new static(
            'The checkout-complete request was incomplete. Possibility of missing post data. Error Code: ' .
            $error['reason'] . ', Message: ' . $error['message']
        );
    }
}
