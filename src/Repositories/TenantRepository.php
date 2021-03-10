<?php

namespace Kyrosoft\Tenant\Repositories;


use Kyrosoft\Tenant\Models\Tenant;
use Kyrosoft\Tenant\Models\User;

class TenantRepository
{
    public function findById(int $id): ?Tenant
    {
        return Tenant::where('id', $id)
            ->whereNull('deleted_at')
            ->first();
    }

    public function findBySubDomain(string $sub_domain): ?Tenant
    {
        return Tenant::where('subdomain', $sub_domain)
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * @param Tenant $tenant
     * @return User[]
     */
    public function getTenantUsers(Tenant $tenant): array
    {
        return $tenant->users();
    }

    public function attachUser(Tenant $tenant, User $user)
    {
        $tenant->users()->attach($user->id);
    }
}