<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $model)
    {
        $this->model = $model;
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data)
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
}
