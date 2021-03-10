<?php
/**
 * Created by PhpStorm.
 * User: fahrur
 * Date: 08/03/21
 * Time: 21:02
 */

namespace Kyrosoft\Tenant\Tests\Unit;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kyrosoft\Tenant\Models\Tenant;
use Kyrosoft\Tenant\Models\User;
use Kyrosoft\Tenant\Providers\CustomUserProvider;
use Kyrosoft\Tenant\Repositories\UserRepository;
use Kyrosoft\Tenant\Tests\TestCase;

class TestLogin extends TestCase
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

    public function testLoginSuccessForCorrectCredentials()
    {
        $user = $this->createUser();
        $is_success = Auth::attempt(['email'=> 'admin@test.com', 'password' => 'admin']);
        $this->assertEquals(true, $is_success);
    }

    public function testLoginFailedForWrongCredentials()
    {
        $user = $this->createUser();
        $is_success = Auth::attempt(['email'=> 'admin@test.com', 'password' => 'aa']);
        $this->assertEquals(false, $is_success);
    }
}