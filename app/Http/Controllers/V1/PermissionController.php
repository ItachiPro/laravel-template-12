<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query("per_page") ?? 10;

        $permissions = Permission::with("roles")->paginate($per_page);

        return ApiResponse::successResponse($permissions, "OK", 200);
    }

    public function show($id)
    {
        $permission = Permission::with("roles")->findOrFail($id);

        return ApiResponse::successResponse($permission, "Permission retrieved successfully.", 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:permissions,name"
        ]);

        $permission = Permission::create([
            "name" => $validated["name"],
            "guard_name" => "web"
        ]);

        return ApiResponse::successResponse($permission, "Permission created successfully.", 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:permissions,name"
        ]);

        $permission = Permission::findOrFail($id);

        $permission->update($validated);

        return ApiResponse::successResponse($permission, "Permission updated successfully.", 200);
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();

        return ApiResponse::successResponse(null, "Permission deleted.", 200);
    }
}
