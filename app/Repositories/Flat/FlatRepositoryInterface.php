<?php

namespace App\Repositories\Flat;

use App\Models\Flat;

interface FlatRepositoryInterface
{
    public function all(?int $limit): array;
    public function find(int $id): ?Flat;
    public function create(array $data): Flat;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
