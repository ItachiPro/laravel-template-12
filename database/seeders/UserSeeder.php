<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
                "name" => "Admin", 
                "password" => "12345678"
            ],
        );

        $adminRole = Role::where("name", "admin")->first();

        if($adminRole && !$adminUser->hasRole("admin")){
            $adminUser->assignRole($adminRole);
        }
    }
}
