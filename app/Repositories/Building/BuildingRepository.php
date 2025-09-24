<?php

namespace App\Repositories\Building;

use App\Models\Building;

class BuildingRepository implements BuildingRepositoryInterface
{
    public function __construct(private Building $buildingModel) {}

    public function all(?int $limit): array
    {
        return $this->buildingModel->when($limit, fn($query) => $query->limit($limit))
            ->get()
            ->toArray();
    }

    public function find(int $id): ?Building
    {
        return $this->buildingModel->find($id);
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
