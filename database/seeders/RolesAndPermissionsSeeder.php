<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions grouped by module
        $modules = [
            "USER" => [
                "LIST",
                "SHOW",
                "CREATE",
                "UPDATE",
                "DELETE",
                "ASSIGN_ROLE",
                "REMOVE_ROLE",
            ],
            "ROLE" => [
                "LIST",
                "SHOW",
                "CREATE",
                "UPDATE",
                "DELETE",
                "ASSIGN_PERMISSION",
                "REMOVE_PERMISSION",
            ],
            "PERMISSION" => [
                "LIST",
                "SHOW",
                "CREATE",
                "UPDATE",
                "DELETE",
            ],
        ];

        $permissions = collect();

        foreach($modules as $module => $actions){
            foreach($actions as $action){
                $permissionName = "{$action}_{$module}";

                $permissions->push(
                    Permission::firstOrCreate([
                        "name" => $permissionName
                    ])
                );
            }
        }

        // Roles
        $superAdmin = Role::firstOrCreate(["name" => "SUPER_ADMIN"]);
        $admin      = Role::firstOrCreate(["name" => "ADMIN"]);
        $manager    = Role::firstOrCreate(["name" => "MANAGER"]);
        $user       = Role::firstOrCreate(["name" => "USER"]);

        // ADMIN have all permissions
        $admin->syncPermissions($permissions);

        // MANAGER all except DELETE and advanced management
        $managerPermissions = $permissions->filter(function ($permission) {
            return !str_starts_with($permission->name, "DELETE_")
                && !str_contains($permission->name, "ASSIGN_PERMISSION");
        });

        $manager->syncPermissions($managerPermissions);

        // USER only has LIST and SHOW
        $userPermissions = $permissions->filter(function ($permission) {
            return str_starts_with($permission->name, "LIST_") || str_starts_with($permission->name, "SHOW_");
        });

        $user->syncPermissions($userPermissions);
    }
}
