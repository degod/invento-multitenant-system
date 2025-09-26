<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\BuildingEditRequest;
use App\Http\Requests\BuildingStoreRequest;
use App\Models\Building;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function __construct(private BuildingRepositoryInterface $buildingRepository, private UserRepositoryInterface $userRepository) {}

    public function index()
    {
        $buildings = $this->buildingRepository->all(config('pagination.default.per_page'), []);
        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);

        return view('buildings.index', compact('buildings', 'owners'));
    }

    public function store(BuildingStoreRequest $request)
    {
        $validated = $request->validated();
        $this->buildingRepository->create($validated);

        return redirect()->route('buildings.index')->with('success', 'Building created successfully.');
    }

    public function edit(int $id)
    {
        $building = $this->buildingRepository->find($id);
        if (!$building) {
            return redirect()->route('buildings.index')->with('error', 'Building not found.');
        }

        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);
        return view('buildings.edit', compact('building', 'owners'));
    }

    public function update(BuildingEditRequest $request, int $id)
    {
        $validated = $request->validated();
        $updated = $this->buildingRepository->update($id, $validated);

        if (!$updated) {
            return redirect()->route('buildings.index')->with('error', 'Building not found or update failed.');
        }

        return redirect()->route('buildings.index')->with('success', 'Building updated successfully.');
    }

    public function destroy(int $id)
    {
        $deleted = $this->buildingRepository->delete($id);

        if (!$deleted) {
            return redirect()->route('buildings.index')->with('error', 'Building not found or delete failed.');
        }

        return redirect()->route('buildings.index')->with('success', 'Building deleted successfully.');
    }
}
