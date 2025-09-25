<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\TenantEditRequest;
use App\Http\Requests\TenantStoreRequest;
use App\Repositories\Tenant\TenantRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class TenantController extends Controller
{
    public function __construct(private TenantRepositoryInterface $tenantRepository, private UserRepositoryInterface $userRepository) {}

    public function index()
    {
        $tenants = $this->tenantRepository->all(config('pagination.default.per_page'));
        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);

        return view('tenants.index', compact('tenants', 'owners'));
    }

    public function store(TenantStoreRequest $request)
    {
        $data = $request->validated();

        $this->tenantRepository->create($data);
        return redirect()->route('tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function edit($id)
    {
        $tenant = $this->tenantRepository->find($id);
        if (!$tenant) {
            return redirect()->route('tenants.index')->with('error', 'Tenant not found.');
        }
        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);
        return view('tenants.edit', compact('tenant', 'owners'));
    }

    public function update(TenantEditRequest $request, int $id)
    {
        $tenant = $this->tenantRepository->find($id);
        if (!$tenant) {
            return redirect()->route('tenants.index')->with('error', 'Tenant not found.');
        }

        $data = $request->validated();

        $this->tenantRepository->update($id, $data);
        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(int $id)
    {
        $tenant = $this->tenantRepository->find($id);
        if (!$tenant) {
            return redirect()->route('tenants.index')->with('error', 'Tenant not found.');
        }

        $this->tenantRepository->delete($id);
        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}
