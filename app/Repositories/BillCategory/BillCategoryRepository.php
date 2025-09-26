<?php

namespace App\Repositories\BillCategory;

use App\Enums\Roles;
use App\Models\Bill;
use App\Models\BillCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BillCategoryRepository implements BillCategoryRepositoryInterface
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private BillCategory $billCategoryModel)
    {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection
    {
        $query = $this->billCategoryModel->newQuery();

        // Apply filters first
        foreach ($filters as $field => $value) {
            $query->where($field, $value);
        }

        if (!$this->isAdmin && !array_key_exists('house_owner_id', $filters)) {
            $query->where('house_owner_id', $this->userId);
        }
        $query->orderBy('id', 'desc');

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }

    public function find(int $id): ?BillCategory
    {
        return $this->billCategoryModel
            ->when(!$this->isAdmin, fn($query) => $query->where('house_owner_id', $this->userId))
            ->whereKey($id)
            ->first();
    }

    public function create(array $data): BillCategory
    {
        return $this->billCategoryModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $billCategory = $this->find($id);
        return $billCategory ? $billCategory->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $billCategory = $this->find($id);
        return $billCategory ? $billCategory->delete() : false;
    }
}