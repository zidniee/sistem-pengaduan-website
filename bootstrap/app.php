<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiTokenAuth;
use App\Http\Middleware\Operator;
use App\Http\Middleware\CheckUserRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 
        $middleware->alias([
            'api.token' => ApiTokenAuth::class,
            'operator' => Operator::class,
            'role' => CheckUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    // 422 - Validation
    $exceptions->render(function (
        \Illuminate\Validation\ValidationException $e,
        $request
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422);
        }

        return redirect()
            ->back()
            ->withErrors($e->errors())
            ->withInput();
    });

    // 401 - Authentication
    $exceptions->render(function (
        \Illuminate\Auth\AuthenticationException $e,
        $request
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        return redirect()->guest(route('login'));
    });

    // 403 - Authorization
    $exceptions->render(function (
        \Illuminate\Auth\Access\AuthorizationException $e,
        $request
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        return response()->view('errors.403', [], 403);
    });

    // 404 - Model not found
    $exceptions->render(function (
        \Illuminate\Database\Eloquent\ModelNotFoundException $e,
        $request
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->view('errors.404', [], 404);
    });

    // 404 - Route not found
    $exceptions->render(function (
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e,
        $request
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Endpoint tidak ditemukan',
            ], 404);
        }

        return response()->view('errors.404', [], 404);
    });

    // 429 - Too many requests
    $exceptions->render(function (
        \Illuminate\Http\Exceptions\ThrottleRequestsException $e,
        $request
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Terlalu banyak permintaan',
            ], 429);
        }

        return response()->view('errors.429', [], 429);
    });

    // 🔥 FINAL FALLBACK (500)
    $exceptions->render(function (
        \Throwable $e,
        $request
    ) {
        if (!app()->isProduction()) {
            throw $e; // DEV: tampilkan error asli
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }

        return response()->view('errors.500', [], 500);
    });
})->create();
