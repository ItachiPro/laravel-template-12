<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignPermissionsRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $per_page = min($request->query("per_page", 10), 100);

        $roles = Role::with("permissions")->orderBy("id", "desc")->paginate($per_page);

        return ApiResponse::successResponse($roles, "Roles retrieved successfully.", 200);
    }

    public function show($id)
    {
        $role = Role::with("permissions")->findOrFail($id);

        return ApiResponse::successResponse($role, "Role retrieved successfully.", 200);
    }

    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        $role = Role::create([
            "name" => $validated["name"],
            "guard_name" => config("auth.defaults.guard")
        ]);

        return ApiResponse::successResponse($role, "Role created successfully.", 201);
    }

    public function assignPermissions(AssignPermissionsRequest $request, $id)
    {
        $validated = $request->validated();

        $role = Role::findOrFail($id);

        $role->syncPermissions($validated["permissions"]);

        return ApiResponse::successResponse($role->load("permissions"), "Permissions synced successfully.", 200);
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $validated = $request->validated();
        
        $role = Role::findOrFail($id);

        $role->update([
            "name" => $validated["name"]
        ]);

        return ApiResponse::successResponse($role, "Role updated successfully.", 200);
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return ApiResponse::deletedResponse();
    }
}
