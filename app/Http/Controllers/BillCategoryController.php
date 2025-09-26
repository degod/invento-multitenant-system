<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\BillCategoryEditRequest;
use App\Http\Requests\BillCategoryStoreRequest;
use App\Repositories\BillCategory\BillCategoryRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BillCategoryController extends Controller
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private BillCategoryRepositoryInterface $billCategoryRepository, private UserRepositoryInterface $userRepository) {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function index()
    {
        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);
        $categories = $this->billCategoryRepository->all(config('pagination.default.per_page'), []);

        return view('bills.categories.index', compact('categories', 'owners'));
    }

    public function store(BillCategoryStoreRequest $request)
    {
        $data = $request->validated();
        
        if (!$this->isAdmin && $data['house_owner_id'] != $this->userId) {
            return redirect()->route('bills.categories.index')->with('error', 'Unauthorized action.');
        }
        $this->billCategoryRepository->create($data);

        return redirect()->back()->with('success', 'Bill category created successfully.');
    }

    public function edit(int $id)
    {
        $category = $this->billCategoryRepository->find($id);
        if (!$category) {
            return redirect()->route('bills.categories.index')->with('error', 'Bill category not found.');
        }
        if (!$this->isAdmin && $category->house_owner_id != $this->userId) {
            return redirect()->route('bills.categories.index')->with('error', 'Unauthorized action.');
        }

        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);

        return view('bills.categories.edit', compact('category', 'owners'));
    }

    public function update(BillCategoryEditRequest $request, int $id)
    {
        $category = $this->billCategoryRepository->find($id);
        if (!$category) {
            return redirect()->route('bills.categories.index')->with('error', 'Bill category not found.');
        }
        if (!$this->isAdmin && $category->house_owner_id != $this->userId) {
            return redirect()->route('bills.categories.index')->with('error', 'Unauthorized action.');
        }

        $data = $request->validated();
        if (!$this->isAdmin && $data['house_owner_id'] != $this->userId) {
            return redirect()->route('bills.categories.index')->with('error', 'Unauthorized action.');
        }

        $this->billCategoryRepository->update($id, $data);

        return redirect()->route('bills.categories.index')->with('success', 'Bill category updated successfully.');
    }

    public function destroy(int $id)
    {
        $category = $this->billCategoryRepository->find($id);
        if (!$category) {
            return redirect()->route('bills.categories.index')->with('error', 'Bill category not found.');
        }
        if (!$this->isAdmin && $category->house_owner_id != $this->userId) {
            return redirect()->route('bills.categories.index')->with('error', 'Unauthorized action.');
        }

        $this->billCategoryRepository->delete($id);

        return redirect()->route('bills.categories.index')->with('success', 'Bill category deleted successfully.');
    }
}