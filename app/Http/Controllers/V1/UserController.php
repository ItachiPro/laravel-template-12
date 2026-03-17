<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query("per_page") ?? 10;

        $users = User::paginate($per_page);

        return ApiResponse::successResponse($users, "OK", 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            
        ]);
        
        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"])
        ]);

        return ApiResponse::successResponse($user, "User created successfully.", 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id)->with("roles")->first();

        return ApiResponse::successResponse($user, "User retrieved successfully.", 200);
    }

    public function assignRoles(Request $request, $id)
    {
        $validated = $request->validate([
            "roles" => "required|array",
            "roles.*" => "exists:roles,name"
        ]);
        
        $user = User::findOrFail($id);

        $user->syncRoles($validated["roles"]);

        return ApiResponse::successResponse($user->load("roles"), "Roles assigned successfully.", 200);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "name" => "required|string|min:4|max:255",
        ]);

        $user = User::findOrFail($id);

        $user->update($validated);

        return ApiResponse::successResponse($user, "User updated successfully.", 200);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return ApiResponse::successResponse(null, "User deleted.", 200);
    }
}
