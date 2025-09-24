<?php

namespace App\Repositories\Flat;

use App\Models\Flat;
use App\Repositories\Flat\FlatRepositoryInterface;

class FlatRepository implements FlatRepositoryInterface
{
    public function __construct(private Flat $flatModel) {}

    public function all(?int $limit): array
    {
        return $this->flatModel->when($limit, fn($query) => $query->limit($limit))
            ->get()
            ->toArray();
    }

    public function find(int $id): ?Flat
    {
        return $this->flatModel->find($id);
    }

    public function create(array $data): Flat
    {
        return $this->flatModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $flat = $this->find($id);
        if ($flat) {
            $flat->fill($data);
            return $flat->save();
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $flat = $this->find($id);
        return $flat ? $flat->delete() : false;
    }
}
