<?php

namespace App\Repositories\Bill;

use App\Enums\Roles;
use App\Models\Bill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BillRepository implements BillRepositoryInterface
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private Bill $billModel)
    {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection
    {
        $query = $this->billModel->newQuery();

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

    public function find(int $id): ?Bill
    {
        return $this->billModel
            ->when(!$this->isAdmin, fn($query) => $query->where('house_owner_id', $this->userId))
            ->whereKey($id)
            ->first();
    }

    public function create(array $data): Bill
    {
        return $this->billModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $bill = $this->find($id);
        return $bill ? $bill->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $bill = $this->find($id);
        return $bill ? $bill->delete() : false;
    }
}