<?php
namespace Kyrosoft\Tenant\Tests;

use Kyrosoft\Tenant\Models\User;
use Kyrosoft\Tenant\MultitenancyServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate',
            ['--database' => 'testbench'])->run();
    }
    /**
     * add the package provider
     *
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            MultitenancyServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('auth.providers.users', [
            'model' => User::class,
            'driver' => 'custom_user',
        ]);
    }
}