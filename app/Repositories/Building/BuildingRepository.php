<?php

namespace App\Repositories\Building;

use App\Enums\Roles;
use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BuildingRepository implements BuildingRepositoryInterface
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private Building $buildingModel)
    {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection
    {
        $query = $this->buildingModel->newQuery();

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

    public function find(int $id): ?Building
    {
        return $this->buildingModel
            ->when(!$this->isAdmin, fn($query) => $query->where('house_owner_id', $this->userId))
            ->whereKey($id)
            ->first();
    }

    public function create(array $data): Building
    {
        return $this->buildingModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $building = $this->find($id);
        return $building ? $building->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $building = $this->find($id);
        return $building ? $building->delete() : false;
    }
}
