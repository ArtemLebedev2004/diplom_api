<?php

namespace App\Exceptions;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if($e instanceof ValidationException && $request->expectsJson()) {

            return response()->json([
                'warning' => [
                    "code" => 422,
                    "messages" => 'Несоответствие требованиям',
                    "warnings" => [
                        $e->errors()
                    ]
                ]
            ], 422);

        } else {
            parent::convertValidationExceptionToResponse($e, $request);
        }
    }


    public function convertExceptionToArray(Throwable $e)
    {
        return config('app.debug') ? [
            'status' => false,
            'messages' => [$e->getMessage()],
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : [
            'status' => false,
            'messages' => [$e->getMessage()],
        ];
    }

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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'warning' => [
                        'code' => 403,
                        'message' => 'Гостевой доступ запрещён'
                    ]
                ], 403);
            }
        });
    }
}
