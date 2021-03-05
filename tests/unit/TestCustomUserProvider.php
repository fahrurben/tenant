<?php

namespace Kyrosoft\Tenant\Tests\Unit;


use Illuminate\Support\Facades\Hash;
use Kyrosoft\Tenant\Models\Tenant;
use Kyrosoft\Tenant\Models\User;
use Kyrosoft\Tenant\Providers\CustomUserProvider;
use Kyrosoft\Tenant\Repositories\UserRepository;
use Kyrosoft\Tenant\Tests\TestCase;

class TestCustomUserProvider extends TestCase
{
    /**
     * @var CustomUserProvider $customUserService
     */
    private $customUserService;

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
        $this->customUserService = new CustomUserProvider($this->userRepository);
    }

    private function createUser()
    {
        $tenant = Tenant::create([
            'name' => 'Company One',
            'sub_domain' => 'comp1',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $user->setRememberToken('test');
        $user->save();

        return $user;
    }

    public function testRetrieveById()
    {
        $user = $this->createUser();
        $retrieveUser = $this->customUserService->retrieveById($user->id);
        $this->assertNotNull($retrieveUser);
    }

    public function testRetrieveByToken()
    {
        $user = $this->createUser();
        $retrieveUser = $this->customUserService->retrieveByToken($user->id, $user->remember_token);
        $this->assertNotNull($retrieveUser);
    }

    public function testUpdateRememberToken()
    {
        $user = $this->createUser();
        $retrieveUser = $this->customUserService->retrieveById($user->id);
        $this->customUserService->updateRememberToken($retrieveUser, 'updated');
        $updatedUser = $this->userRepository->findById($retrieveUser->id);
        $this->assertEquals('updated', $updatedUser->remember_token);
    }

    public function testRetrieveByCredentials()
    {
        $user = $this->createUser();
        $retrieveUser = $this->customUserService->retrieveByCredentials([
            'email' => 'admin@test.com',
            'password' => 'test'
        ]);
        $this->assertNotNull($retrieveUser);
    }

    public function testValidateCredentials()
    {
        $user = $this->createUser();
        $isValid = $this->customUserService->validateCredentials($user, [
            'email' => 'admin@test.com',
            'password' => 'admin'
        ]);
        $this->assertTrue($isValid);
    }
}