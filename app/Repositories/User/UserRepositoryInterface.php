<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function create(array $data): User;
    public function update(string $id, array $data): ?User;
    public function delete(string $id): bool;
    public function all(?int $perPage): LengthAwarePaginator|Collection;
    public function allByRole(string $role, ?int $perPage): LengthAwarePaginator|Collection;
}
