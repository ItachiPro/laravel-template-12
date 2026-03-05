<?php

use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\PermissionController;
use App\Http\Controllers\V1\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function(){
    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);

    Route::middleware("auth:sanctum")->group(function(){
        Route::post("logout", [AuthController::class, "logout"]);
        Route::get("me", [AuthController::class, "me"]);

        // User
        Route::get("users", [UserController::class, "index"])->middleware("permission:LIST_USER");
        Route::group(["prefix" => "user"], function(){
            Route::post("/", [UserController::class, "store"])->middleware("permission:CREATE_USER");
            Route::get("/{id}", [UserController::class, "show"])->middleware("permission:SHOW_USER");
            Route::put("/{id}", [UserController::class, "update"])->middleware("permission:UPDATE_USER");
            Route::delete("/{id}", [UserController::class, "destroy"])->middleware("permission:DELETE_USER");
            Route::post("/{id}/roles", [UserController::class, "assignRoles"])->middleware("permission:ASSIGN_ROLE_USER");
        });

        // Role
        Route::get("roles", [RoleController::class, "index"])->middleware("permission:LIST_ROLE");
        Route::group(["prefix" => "role"], function(){
            Route::post("/", [RoleController::class, "store"])->middleware("permission:CREATE_ROLE");
            Route::get("/{id}", [RoleController::class, "show"])->middleware("permission:SHOW_ROLE");
            Route::put("/{id}", [RoleController::class, "update"])->middleware("permission:UPDATE_ROLE");
            Route::delete("/{id}", [RoleController::class, "destroy"])->middleware("permission:DELETE_ROLE");
            Route::post("/{id}/permissions", [RoleController::class, "assignPermissions"])->middleware("permission:ASSIGN_PERMISSION_ROLE");
        });

        // Permission
        Route::get("permissions", [PermissionController::class, "index"])->middleware("permission:LIST_PERMISSION");
        Route::group(["prefix" => "permission"], function(){
            Route::post("/", [PermissionController::class, "store"])->middleware("permission:CREATE_PERMISSION");
            Route::get("/{id}", [PermissionController::class, "show"])->middleware("permission:SHOW_PERMISSION");
            Route::put("/{id}", [PermissionController::class, "update"])->middleware("permission:UPDATE_PERMISSION");
            Route::delete("/{id}", [PermissionController::class, "destroy"])->middleware("permission:DELETE_PERMISSION");
        });
    });
});