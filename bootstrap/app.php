<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\JwtMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt' => JwtMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        // 簡化 JSON 錯誤格式
        $exceptions->renderable(function (Throwable $e, Request $request) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;

            if (method_exists($e, 'getStatusCode')) {
                $status = $e->getStatusCode();
            }

            return response()->json([
                'message' => $e->getMessage(),
            ], $status);
        });
    })->create();
