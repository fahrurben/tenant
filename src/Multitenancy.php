<?php

namespace Kyrosoft\Tenant;


use Illuminate\Database\Eloquent\Model;
use Kyrosoft\Tenant\Models\Tenant;
use Kyrosoft\Tenant\Repositories\TenantRepository;

class Multitenancy
{
    /**
     * @var Tenant $tenant
     */
    protected $tenant = null;

    /**
     * @var TenantRepository $tenantRepository
     */
    protected $tenantRepository = null;

    /**
     * Models that need scopes before the app fully boots
     * they will be processed at a later time.
     *
     * @var collect
     */
    protected $deferredModels;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
        $this->deferredModels = collect();
    }

    public function setTenant(Tenant $tenant)
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function currentTenant(): Tenant
    {
        return $this->tenant ?? $this->receiveTenantFromRequest();
    }

    /**
     * Applies applicable tenant scopes to model or if not booted yet
     * store for deferment.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return void|null
     */
    public function applyTenantScope(Model $model)
    {
        if (is_null($this->tenant)) {
            $this->deferredModels->push($model);

            return;
        }

        if ('admin' === $this->tenant->domain) {
            return;
        }

        $model->addGlobalScope('tenant', function (Builder $builder) use ($model) {
            $builder->where($model->qualifyColumn('tenant_id'), '=', $this->tenant->id);
        });
    }

    /**
     * Determines how best to process the URL based
     * on config and then returns the appropriate
     * subdomain text.
     *
     * @return string
     */
    public function getCurrentSubDomain(): string
    {
        return $this->getSubDomainBasedOnHTTPHost();
    }

    /**
     * Parses the request to pull out the first element separated
     * by `.` in the $_SERVER['HTTP_HOST'].
     *
     * ex:
     * test.domain.com returns test
     * test2.test.domain.com returns test2
     *
     * @return string
     */
    protected function getSubDomainBasedOnHTTPHost(): string
    {
        $currentDomain = app('request')->getHost();

        // Get rid of the TLD and root domain
        // ex: masterdomain.test.example.com returns
        // [ masterdomain, test ]
        $subdomains = explode('.', $currentDomain, -2);

        // Combine multiple level of domains into 1 string
        // ex: back to masterdomain.test
        $subdomain = implode('.', $subdomains);

        return $subdomain;
    }

    public function receiveTenantFromRequest()
    {
        $sub_domain = $this->getCurrentSubDomain();

        return $this->tenantRepository->findBySubDomain($sub_domain);
    }
}