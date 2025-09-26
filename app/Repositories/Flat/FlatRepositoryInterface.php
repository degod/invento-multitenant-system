<?php

namespace App\Repositories\Flat;

use App\Models\Flat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface FlatRepositoryInterface
{
    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection;
    public function find(int $id): ?Flat;
    public function create(array $data): Flat;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
