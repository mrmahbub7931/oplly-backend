<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use League\OAuth2\Server\Exception\OAuthServerException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @param Exception $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        // Kill reporting if this is an "access denied" (code 9) or "invalid grant" (code 6) OAuthServerException.
        if (
            $exception instanceof OAuthServerException &&
            ($exception->getCode() === 9 || $exception->getCode() === 6)
        ) {
            return;
        }

        if (
            $exception instanceof \Laravel\Passport\Exceptions\OAuthServerException  &&
            ($exception->getCode() === 9 || $exception->getCode() === 6)
        ) {
            return;
        }

        parent::report($exception);
    }

}
