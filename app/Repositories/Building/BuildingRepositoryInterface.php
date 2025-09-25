<?php

namespace App\Repositories\Building;

use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BuildingRepositoryInterface
{
    public function all(?int $perPage): LengthAwarePaginator|Collection;

    public function find(int $id): ?Building;

    public function create(array $data): Building;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
