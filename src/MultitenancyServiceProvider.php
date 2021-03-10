<?php
namespace Kyrosoft\Tenant;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Kyrosoft\Tenant\Providers\CustomUserProvider;
use Kyrosoft\Tenant\Repositories\TenantRepository;
use Kyrosoft\Tenant\Repositories\UserRepository;

class MultitenancyServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Multitenancy::class, function () {
            return new Multitenancy(new TenantRepository());
        });

        $this->app->alias(Multitenancy::class, 'multitenancy');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Auth::provider('custom_user', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new CustomUserProvider(new UserRepository());
        });
    }
}