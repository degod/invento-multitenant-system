<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant;

interface TenantRepositoryInterface
{
    public function all(?int $limit): array;
    public function find(int $id): ?Tenant;
    public function create(array $data): Tenant;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
