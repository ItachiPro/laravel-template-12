<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email"=> "required|string|email|unique:users",
            "password" => "required|string|min:8|confirmed"
        ]);

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"])
        ]);

        $user->assignRole("user");

        $token = $user->createToken("api-token")->plainTextToken;

        return $this->success([
            "user" => $user,
            "token" => $token
        ], "User registered successfully.", 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if(!Auth::attempt($request->only("email", "password"))){
            return $this->error(
                "Invalid credentials.",
                ["email" => ["Invalid credentials"]],
                401
            );
        }

        $user = User::where("email", $request->email)->firstOrFail();

        $token = $user->createToken("api-token")->plainTextToken;

        return $this->success([
            "user" => $user,
            "token" => $token
        ], "User logged.", 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(
            null,
            "Logout successfully.",
            200
        );
    }

    public function me(Request $request)
    {
        return $this->success(
            $request->user(),
            "User authenticate.",
            200
        );
    }
}