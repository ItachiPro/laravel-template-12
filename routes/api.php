<?php

use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\AuthController;
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

        // Role
        Route::get("roles", [RoleController::class, "index"])->middleware("permissions:LIST_ROLE");
        Route::group(["prefix" => "role"], function(){
            Route::post("/", [RoleController::class, "store"])->middleware("permissions:CREATE_ROLE");
            Route::delete("/{id}", [RoleController::class, "destroy"])->middleware("permissions:DELETE_ROLE");
        });
    });
});