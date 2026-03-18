<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $per_page = min($request->query("per_page", 10), 100);

        $permissions = Permission::with("roles")->orderBy("id", "desc")->paginate($per_page);

        return ApiResponse::successResponse($permissions, "Permissions retrieved successfully.", 200);
    }

    public function show($id)
    {
        $permission = Permission::with("roles")->findOrFail($id);

        return ApiResponse::successResponse($permission, "Permission retrieved successfully.", 200);
    }

    public function store(StorePermissionRequest $request)
    {
        $validated = $request->validated();

        $permission = Permission::create([
            "name" => $validated["name"],
            "guard_name" => config("auth.defaults.guard")
        ]);

        return ApiResponse::successResponse($permission, "Permission created successfully.", 201);
    }

    public function update(UpdatePermissionRequest $request, $id)
    {
        $validated = $request->validated();

        $permission = Permission::findOrFail($id);

        $permission->update([
            "name" => $validated["name"]
        ]);

        return ApiResponse::successResponse($permission, "Permission updated successfully.", 200);
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();

        return ApiResponse::deletedResponse();
    }
}
