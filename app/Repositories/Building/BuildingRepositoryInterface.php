<?php

namespace App\Repositories\Building;

use App\Models\Building;

interface BuildingRepositoryInterface
{
    public function all(?int $limit): array;

    public function find(int $id): ?Building;

    public function create(array $data): Building;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
