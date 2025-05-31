<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof HttpException && $exception->getStatusCode() == 404) {

            return response()->json([
                'status'  => false,
                'message' => "La solicitud no ha sido encontrada en el servidor.",
                'errors'  => null,
                'data'    => null,
            ], 404);
        }

        // if ($exception instanceof HttpException && $exception->getStatusCode() == 403) {

        //     return response()->json([
        //         'status'  => false,
        //         'message' => "No tiene el acceso.",
        //         'errors'  => null,
        //         'data'    => null,
        //     ], 404);
        // }

        return parent::render($request, $exception);
    }
}
