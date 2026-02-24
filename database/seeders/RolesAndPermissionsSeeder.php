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

        $permissions = [
            "LIST_USER",
            "CREATE_USER",
            "UPDATE_USER",
            "DELETE_USER",
            "SHOW_USER",
            "LIST_ROLE",
            "CREATE_ROLE",
            "UPDATE_ROLE",
            "DELETE_ROLE",
            "SHOW_ROLE",
            "LIST_PERMISSION",
            "CREATE_PERMISSION",
            "UPDATE_PERMISSION",
            "DELETE_PERMISSION",
            "SHOW_PERMISSION",
        ];

        foreach($permissions as $permission){
            Permission::firstOrCreate(["name" => $permission]);
        }

        $admin = Role::firstOrCreate(["name" => "admin"]);
        $user = Role::firstOrCreate(["name" => "user"]);

        $admin->givePermissionTo(Permission::all());

        $user->givePermissionTo("SHOW_USER");
    }
}
