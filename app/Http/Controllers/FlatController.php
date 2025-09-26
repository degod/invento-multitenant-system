<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\FlatEditRequest;
use App\Http\Requests\FlatStoreRequest;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Repositories\Flat\FlatRepositoryInterface;
use App\Repositories\Tenant\TenantRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlatController extends Controller
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private FlatRepositoryInterface $flatRepository, private TenantRepositoryInterface $tenantRepository, private BuildingRepositoryInterface $buildingRepository, private UserRepositoryInterface $userRepository, private LogService $logService)
    {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function index()
    {
        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);
        $filters = [];
        if (!$this->isAdmin) {
            $filters['house_owner_id'] = $this->userId;
        }
        $buildings = $this->buildingRepository->all(null, $filters);
        $tenants = $this->tenantRepository->all(null, $filters);
        $flats = $this->flatRepository->all(config('pagination.default.per_page'), $filters);

        return view('flats.index', compact('owners', 'buildings', 'tenants', 'flats'));
    }

    public function store(FlatStoreRequest $request)
    {
        $data = $request->validated();

        if (!$this->isAdmin && $data['house_owner_id'] != $this->userId) {
            return redirect()->route('flats.index')->with('error', 'Unauthorized action.');
        }

        try {
            $this->flatRepository->create($data);
        } catch (\Exception $e) {
            $this->logService->error('Error creating flat: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('flats.index')->with('error', $e->getMessage());
        }

        return redirect()->route('flats.index')->with('success', 'Flat created successfully.');
    }

    public function edit(int $id)
    {
        $flat = $this->flatRepository->find($id);
        if (!$flat) {
            return redirect()->route('flats.index')->with('error', 'Flat not found.');
        }
        if (!$this->isAdmin && $flat->house_owner_id != $this->userId) {
            return redirect()->route('flats.index')->with('error', 'Unauthorized action.');
        }

        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);
        $filters = [];
        if (!$this->isAdmin) {
            $filters['house_owner_id'] = $this->userId;
        }
        $buildings = $this->buildingRepository->all(null, $filters);
        $tenants = $this->tenantRepository->all(null, $filters);

        return view('flats.edit', compact('flat', 'owners', 'buildings', 'tenants'));
    }

    public function update(FlatEditRequest $request, int $id)
    {
        $flat = $this->flatRepository->find($id);
        if (!$flat) {
            return redirect()->route('flats.index')->with('error', 'Flat not found.');
        }
        if (!$this->isAdmin && $flat->house_owner_id != $this->userId) {
            return redirect()->route('flats.index')->with('error', 'Unauthorized action.');
        }

        $data = $request->validated();
        if (!$this->isAdmin && $data['house_owner_id'] != $this->userId) {
            return redirect()->route('flats.index')->with('error', 'Unauthorized action.');
        }

        try {
            $this->flatRepository->update($id, $data);
        } catch (\Exception $e) {
            $this->logService->error('Error updating flat: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('flats.index')->with('error', $e->getMessage());
        }

        return redirect()->route('flats.index')->with('success', 'Flat updated successfully.');
    }

    public function destroy(int $id)
    {
        $flat = $this->flatRepository->find($id);
        if (!$flat) {
            return redirect()->route('flats.index')->with('error', 'Flat not found.');
        }
        if (!$this->isAdmin && $flat->house_owner_id != $this->userId) {
            return redirect()->route('flats.index')->with('error', 'Unauthorized action.');
        }

        try {
            $this->flatRepository->delete($id);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting flat: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('flats.index')->with('error', $e->getMessage());
        }

        return redirect()->route('flats.index')->with('success', 'Flat deleted successfully.');
    }

    public function filter(int $id, Request $request)
    {
        $filters['house_owner_id'] = $id;

        $buildings = $this->buildingRepository->all(null, $filters);
        $tenants = $this->tenantRepository->all(null, $filters);

        return response()->json(compact('buildings', 'tenants'));
    }
}
