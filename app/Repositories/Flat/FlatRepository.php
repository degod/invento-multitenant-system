<?php

namespace App\Repositories\Flat;

use App\Enums\Roles;
use App\Models\Flat;
use App\Repositories\Flat\FlatRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class FlatRepository implements FlatRepositoryInterface
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private Flat $flatModel)
    {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection
    {
        $query = $this->flatModel->newQuery();

        // Apply filters first
        foreach ($filters as $key => $value) {
            if (!is_null($value)) {
                $query->where($key, $value);
            }
        }

        if (!$this->isAdmin && !array_key_exists('house_owner_id', $filters)) {
            $query->where('house_owner_id', $this->userId);
        }
        $query->orderBy('id', 'desc');

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }

    public function find(int $id): ?Flat
    {
        return $this->flatModel->when(!$this->isAdmin, fn($query) => $query->where('house_owner_id', $this->userId))
            ->find($id);
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
