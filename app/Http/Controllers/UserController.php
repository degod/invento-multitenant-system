<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserStoreRequest;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function index()
    {
        $users = $this->userRepository->all(config('pagination.default.per_page'));
        $roles = array_values((new \ReflectionClass(Roles::class))->getConstants());
        $roles = array_combine($roles, array_map(fn($role) => ucfirst(str_replace('-', ' ', $role)), $roles));

        return view('users.index', compact('users', 'roles'));
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make('123abc123');
        $this->userRepository->create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully. Default password is "123abc123".');
    }

    public function edit(int $id)
    {
        $user = $this->userRepository->findById($id);
        $roles = array_values((new \ReflectionClass(Roles::class))->getConstants());
        $roles = array_combine($roles, array_map(fn($role) => ucfirst(str_replace('-', ' ', $role)), $roles));

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UserEditRequest $request, int $id)
    {
        $data = $request->validated();
        $this->userRepository->update($id, $data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->userRepository->delete($id);
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
