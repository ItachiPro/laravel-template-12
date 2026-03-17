<?php

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(HandleCors::class);
        $middleware->alias([
            "permission" => PermissionMiddleware::class,
            "role" => RoleMiddleware::class,
            "role_or_permission" => RoleOrPermissionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (UnauthorizedException $e, Request $request){
            if($request->is("api/*")){
                return ApiResponse::errorResponse(
                    "User does not have the right permissions.",
                    null,
                    Response::HTTP_FORBIDDEN
                );
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request){
            if($request->is("api/*")){
                return ApiResponse::errorResponse(
                    "Validation error.",
                    $e->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request){
            if($request->is("api/*")){
                return ApiResponse::errorResponse(
                    "Unauthenticated.",
                    null,
                    Response::HTTP_UNAUTHORIZED
                );
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is("api/*")) {
                return ApiResponse::errorResponse(
                    "Resource not found.",
                    null,
                    Response::HTTP_NOT_FOUND
                );
            }
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is("api/*")) {
                return ApiResponse::errorResponse(
                    $e->getMessage() ?: "HTTP error.",
                    null,
                    $e->getStatusCode()
                );
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is("api/*")) {
                return ApiResponse::errorResponse(
                    "Server error.",
                    config('app.debug') ? $e->getMessage() : null,
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        });
    })->create();
