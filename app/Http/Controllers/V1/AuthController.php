<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $user->assignRole("USER");

        $token = $user->createToken("api-token")->plainTextToken;

        return ApiResponse::successResponse([
            "user" => $user,
        ], "User registered successfully.", 201)->cookie(
            "token",
            $token,
            60 * 24 * 7, // 7 days
            "/",
            null,
            false, // secure (true en https)
            true, // httpOnly
            false,
            "Lax"
        );
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only("email", "password");

        if(!Auth::attempt($credentials)){
            return ApiResponse::errorResponse("Invalid credentials.", [
                "email" => ["The provided credentials are incorrect."]
            ], 401);
        }

        $user = User::where("email", $request->email)->firstOrFail();

        $token = $user->createToken("api-token")->plainTextToken;

        return ApiResponse::successResponse([
            "user" => $user,
            "token" => $token,
        ], "User logged.", 200)->cookie(
            "token",
            $token,
            60 * 24 * 7, // 7 days
            "/",
            null,
            false, // secure (true en https)
            true, // httpOnly
            false,
            "Lax"
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::successResponse(
            null,
            "Logout successfully.",
            200
        )->cookie(
            "token",
            "",
            -1
        );
    }

    public function me(Request $request)
    {
        return ApiResponse::successResponse(
            $request->user(),
            "User authenticate.",
            200
        );
    }
}