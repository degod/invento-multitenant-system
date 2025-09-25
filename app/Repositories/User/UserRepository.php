<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): ?User
    {
        $record = $this->findById($id);
        if (!$record) return null;
        $record->update($data);
        return $record;
    }

    public function delete(string $id): bool
    {
        $record = $this->findById($id);
        if (!$record) return false;
        return $record->delete();
    }

    public function all(?int $perPage): LengthAwarePaginator|Collection
    {
        $users = $this->model->orderBy('id', 'DESC');
        return $perPage ? $users->paginate($perPage) : $users->get();
    }

    public function allByRole(string $role, ?int $perPage): LengthAwarePaginator|Collection
    {
        $users = $this->model->where('role', $role)->orderBy('id', 'DESC');
        return $perPage ? $users->paginate($perPage) : $users->get();
    }
}
