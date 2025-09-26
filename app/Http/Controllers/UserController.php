<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserStoreRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\LogService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository, private LogService $logService) {}

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
        $data['password'] = Hash::make('password');

        try {
            $this->userRepository->create($data);
        } catch (\Exception $e) {
            $this->logService->error('Error creating user: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User created successfully. Default password is "password".');
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

        try {
            $this->userRepository->update($id, $data);
        } catch (\Exception $e) {
            $this->logService->error('Error updating user: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        try {
            $this->userRepository->delete($id);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting user: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
