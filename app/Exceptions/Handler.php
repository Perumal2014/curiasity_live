<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function register()
    {
        $this->reportable(function (Throwable $exception) {

            if ($exception instanceof ModelNotFoundException) {
                abort(404, '');
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                abort(500, $exception->getMessage());
            }

            if ($exception instanceof TokenMismatchException) {

                $tenant = request()->segment(1);

                return redirect()->guest(url($tenant . '/login'));
            }
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $tenant = request()->segment(1);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return redirect()->guest(url($tenant . '/login'));
    }
}