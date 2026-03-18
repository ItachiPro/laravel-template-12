<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRolesRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $per_page = min($request->query("per_page", 10), 100);

        $users = User::orderBy("id", "desc")->paginate($per_page);

        return ApiResponse::successResponse($users, "Users retrieved successfully.", 200);
    }

    public function show($id)
    {
        $user = User::with("roles")->findOrFail($id);

        return ApiResponse::successResponse($user, "User retrieved successfully.", 200);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"])
        ]);

        return ApiResponse::successResponse($user, "User created successfully.", 201);
    }

    public function assignRoles(AssignRolesRequest $request, $id)
    {
        $validated = $request->validated();
        
        $user = User::findOrFail($id);

        $user->syncRoles($validated["roles"]);

        return ApiResponse::successResponse($user->load("roles"), "Roles assigned successfully.", 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);
        
        if(isset($validated["password"])){
            $validated["password"] = Hash::make($validated["password"]);
        }

        $user->update($validated);

        return ApiResponse::successResponse($user, "User updated successfully.", 200);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return ApiResponse::deletedResponse();
    }
}
