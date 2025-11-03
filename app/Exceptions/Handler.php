<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested resource was not found.',
                    'error_code' => 'not_found',
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (\Exception $e, $request) {
            if ($request->is('api/*') && $this->isHttpException($e)) {
                $statusCode = $e->getStatusCode();
                
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'An error occurred',
                    'error_code' => $this->getErrorCode($statusCode),
                ], $statusCode);
            }
        });

        $this->renderable(function (\Exception $e, $request) {
            if ($request->is('api/*') && !$this->isHttpException($e) && config('app.debug') === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Internal server error',
                    'error_code' => 'internal_server_error',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }

    /**
     * Convert a validation exception into a JSON response.
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => $exception->errors(),
            'error_code' => 'validation_error',
        ], $exception->status);
    }

    /**
     * Get the error code based on HTTP status code.
     */
    protected function getErrorCode(int $statusCode): string
    {
        return match($statusCode) {
            400 => 'bad_request',
            401 => 'unauthorized',
            403 => 'forbidden',
            404 => 'not_found',
            405 => 'method_not_allowed',
            419 => 'page_expired',
            422 => 'validation_error',
            429 => 'too_many_requests',
            500 => 'internal_server_error',
            503 => 'service_unavailable',
            default => 'error_'.$statusCode,
        };
    }
}
