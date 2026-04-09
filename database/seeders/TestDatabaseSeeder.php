<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleAndSuperAdminSeeder::class);
    }
}