<?php

namespace App\Repositories\BillCategory;

use App\Models\BillCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BillCategoryRepositoryInterface
{
    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection;

    public function find(int $id): ?BillCategory;

    public function create(array $data): BillCategory;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}