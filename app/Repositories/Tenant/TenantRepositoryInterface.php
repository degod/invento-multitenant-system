<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TenantRepositoryInterface
{
    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection;
    public function find(int $id): ?Tenant;
    public function create(array $data): Tenant;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
