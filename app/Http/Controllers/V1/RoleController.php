<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query("per_page") ?? 10;

        $roles = Role::with("permissions")->paginate($per_page);

        return ApiResponse::successResponse($roles, "OK", 200);
    }

    public function show($id)
    {
        $role = Role::with("permissions")->findOrFail($id);

        return ApiResponse::successResponse($role, "Role retrieved successfully.", 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:roles,name"
        ]);

        $role = Role::create([
            "name" => $validated["name"],
            "guard_name" => "web"
        ]);

        return ApiResponse::successResponse($role, "Role created successfully.", 201);
    }

    public function assignPermissions(Request $request, $id)
    {
        $validated = $request->validate([
            "permissions" => "required|array",
            "permissions.*" => "exists:permissions,name"
        ]);

        $role = Role::findOrFail($id);

        $role->syncPermissions($validated["permissions"]);

        return ApiResponse::successResponse($role->load("permissions"), "Permissions added successfully.", 200);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:roles,name"
        ]);
        
        $role = Role::findOrFail($id);

        $role->update($validated);

        return ApiResponse::successResponse($role, "Role updated successfully.", 200);
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return ApiResponse::successResponse(null, "Rol deleted.", 200);
    }
}
