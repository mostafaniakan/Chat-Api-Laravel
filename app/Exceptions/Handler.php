<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Throwable;
use App\Traits\ApiResponse;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {

        if ($e->getMessage() == "Too Many Attempts.") {
            return $this->errorResponse($e->getMessage(), 429);
        }
        if ($e instanceof ModelNotFoundException) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        if (config('app.debug')) {
            return parent::render($request, $e);
        }
        if ($e instanceof InternalErrorException) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }
}
