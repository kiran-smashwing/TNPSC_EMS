<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {


        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->append(\App\Http\Middleware\SanitizeInput::class);
        // Add the multi-auth middleware
        $middleware->alias([
            'auth.multi' => \App\Http\Middleware\MultiAuthMiddleware::class,
            // Add the role permission middleware
            'role.permission' => \App\Http\Middleware\RolePermissionMiddleware::class,
            // Add the redirect if authenticated middleware
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            // Add the check session middleware
            'check.session' => \App\Http\Middleware\CheckSession::class
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($exception->getStatusCode() == 404) {

                return response()->view("errors.404", [], 404);
            }
        });

    })->create();
