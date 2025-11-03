<?php

use App\Http\Middleware\JsonResponseMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [JsonResponseMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, Request $request)  use ($exceptions){
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Data not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $exceptions->render(function (Throwable $e, Request $request) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    $response = [
                        'message' => 'Internal server error',
                    ];

                    if (config('app.debug')) {
                        $response['error'] = $e->getMessage();
                        $response['trace'] = $e->getTraceAsString();
                    }

                    return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            });

        });
    })->create();
