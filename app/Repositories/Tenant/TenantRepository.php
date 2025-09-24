<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant;

class TenantRepository implements TenantRepositoryInterface
{
    public function __construct(private Tenant $model) {}

    public function all(?int $limit): array
    {
        return $this->model->limit($limit)->get()->all();
    }

    public function find(int $id): ?Tenant
    {
        return $this->model->find($id);
    }

    public function create(array $data): Tenant
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $tenant = $this->model->find($id);
        return $tenant ? $tenant->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $tenant = $this->model->find($id);
        return $tenant ? $tenant->delete() : false;
    }
}
