<?php

namespace App\Repositories\Bill;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BillRepositoryInterface
{
    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection;

    public function getAllDues(): LengthAwarePaginator|Collection;

    public function find(int $id): ?Bill;

    public function create(array $data): Bill;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getUnpaidBillsForFlat(int $flatId): Collection;
}
