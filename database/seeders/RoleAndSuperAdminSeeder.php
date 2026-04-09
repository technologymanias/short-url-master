<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndSuperAdminSeeder extends Seeder
{
    public function run()
    {
       
        $roles = [
            'SuperAdmin',
            'Client Admin',
            'Client Member'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

      
        $superAdminRole = Role::where('name', 'SuperAdmin')->first();

        User::firstOrCreate(
            ['email' => 'super@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'company_id' => 1,
            ]
        );
    }
}