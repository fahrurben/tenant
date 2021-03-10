<?php
namespace Kyrosoft\Tenant\Tests\Unit;

use Illuminate\Support\Facades\Hash;
use Kyrosoft\Tenant\Models\Tenant;
use Kyrosoft\Tenant\Models\User;
use Kyrosoft\Tenant\Tests\TestCase;

class TestCreateModels extends TestCase
{
    public function testTenantCreation()
    {
        $tenant = Tenant::create([
            'name' => 'Company One',
            'sub_domain' => 'comp1',
        ]);
        $this->assertNotNull($tenant);
    }

    public function testUserCreation()
    {
        $tenant = Tenant::create([
            'name' => 'Company One',
            'sub_domain' => 'comp1',
        ]);
        $this->assertNotNull($tenant);

        $user = User::create([
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $this->assertNotNull($user);

        $tenant->users()->attach($user);

        $this->assertEquals(1, count($tenant->users));
    }
}