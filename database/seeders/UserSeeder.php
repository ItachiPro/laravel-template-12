<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::firstOrCreate(
            [
                "email" => "wltrstnly@hotmail.com",
            ],
            [
                "name" => "SUPER_ADMIN", 
                "password" => Hash::make(env("SUPER_ADMIN_PASSWORD","12345678")),
            ]
        );

        $adminRole = Role::where("name", "SUPER_ADMIN")->first();

        if($adminRole && !$adminUser->hasRole("SUPER_ADMIN")){
            $adminUser->assignRole($adminRole);
        }
    }
}
