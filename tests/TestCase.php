<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function refreshTestDatabase()
    {
        parent::refreshTestDatabase();
        
        // Seed roles after the database is refreshed
        $this->artisan('db:seed', ['--class' => 'RoleAndSuperAdminSeeder']);
    }
}