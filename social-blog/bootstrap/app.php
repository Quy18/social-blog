<?php

use App\Providers\PostServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        PostServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',           // THÊM DÒNG NÀY
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',                           // TỰ ĐỘNG THÊM /api
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ĐĂNG KÝ ALIAS CHO JWT (bắt buộc Laravel 11)
        $middleware->alias([
            'jwt.auth'    => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
        ]);

        // TẮT HOÀN TOÀN SANCTUM (nếu không dùng)
        $middleware->api(prepend: []);           // giết chết EnsureFrontendRequestsAreStateful
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
