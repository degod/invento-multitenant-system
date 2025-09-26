<?php

namespace App\Repositories\Tenant;

use App\Enums\Roles;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TenantRepository implements TenantRepositoryInterface
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private Tenant $model)
    {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection
    {
        $query = $this->model->newQuery();

        // Apply filters first
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        if (!$this->isAdmin && !array_key_exists('house_owner_id', $filters)) {
            $query->where('house_owner_id', $this->userId);
        }
        $query->orderBy('id', 'desc');

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }

    public function find(int $id): ?Tenant
    {
        // filter by house_owner_id if not admin
        return $this->model->when(!$this->isAdmin, fn($q) => $q->where('house_owner_id', $this->userId))
            ->whereKey($id)
            ->first();
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
