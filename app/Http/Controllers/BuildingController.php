<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\BuildingEditRequest;
use App\Http\Requests\BuildingStoreRequest;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\LogService;

class BuildingController extends Controller
{
    public function __construct(private BuildingRepositoryInterface $buildingRepository, private UserRepositoryInterface $userRepository, private LogService $logService) {}

    public function index()
    {
        $buildings = $this->buildingRepository->all(config('pagination.default.per_page'), []);
        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);

        return view('buildings.index', compact('buildings', 'owners'));
    }

    public function store(BuildingStoreRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->buildingRepository->create($validated);
        } catch (\Exception $e) {
            $this->logService->error('Error creating building: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('buildings.index')->with('error', $e->getMessage());
        }

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
        try {
            $this->buildingRepository->update($id, $validated);
        } catch (\Exception $e) {
            $this->logService->error('Error updating building: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('buildings.index')->with('error', $e->getMessage());
        }

        return redirect()->route('buildings.index')->with('success', 'Building updated successfully.');
    }

    public function destroy(int $id)
    {
        try {
            $this->buildingRepository->delete($id);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting building: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('buildings.index')->with('error', $e->getMessage());
        }

        return redirect()->route('buildings.index')->with('success', 'Building deleted successfully.');
    }
}
