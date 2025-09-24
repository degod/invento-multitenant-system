<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function findById(int $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id): bool;
}
