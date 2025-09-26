<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Requests\BillEditRequest;
use App\Http\Requests\BillStoreRequest;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\BillCategory\BillCategoryRepositoryInterface;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Repositories\Flat\FlatRepositoryInterface;
use App\Repositories\Tenant\TenantRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\EmailService;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private BillRepositoryInterface $billRepository, private UserRepositoryInterface $userRepository, private BillCategoryRepositoryInterface $billCategoryRepository, private FlatRepositoryInterface $flatRepository, private TenantRepositoryInterface $tenantRepository, private BuildingRepositoryInterface $buildingRepository, private LogService $logService, private EmailService $emailService)
    {
        $this->isAdmin = Auth::user()->role === 'admin';
        $this->userId = Auth::id();
    }

    public function index()
    {
        $filters = [];
        if (!$this->isAdmin) {
            $filters['house_owner_id'] = $this->userId;
        }
        $bills = $this->billRepository->all(config('pagination.default.per_page'), $filters);
        $categories = $this->billCategoryRepository->all(null, $filters);
        $flats = $this->flatRepository->all(null, $filters);
        $owners = $this->userRepository->allByRole(Roles::HOUSE_OWNER, null);

        return view('bills.index', compact('bills', 'categories', 'flats', 'owners'));
    }

    public function store(BillStoreRequest $request)
    {
        $data = $request->validated();
        if (!$this->isAdmin) {
            $data['house_owner_id'] = $this->userId;
        }

        try {
            $data['month'] = Carbon::createFromFormat('Y-m', $data['month'])->format('F Y');
            $this->billRepository->create($data);

            // Send email notification to tenant
            $flat = $this->flatRepository->find($data['flat_id']);
            $tenant = $this->tenantRepository->find($flat->tenant_id);

            if ($tenant) {
                $building = $this->buildingRepository->find($flat->building_id);
                $this->emailService->sendBillNotification($tenant->email, $data, $flat, $building);
            }
        } catch (\Exception $e) {
            $this->logService->error('Error creating bill: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('bills.index')->with('error', $e->getMessage());
        }

        return redirect()->route('bills.index')->with('success', 'Bill created successfully.');
    }

    public function edit(int $id)
    {
        $bill = $this->billRepository->find($id);
        if (!$bill) {
            return redirect()->route('bills.index')->with('error', 'Bill not found.');
        }

        if (!$this->isAdmin && $bill->house_owner_id !== $this->userId) {
            return redirect()->route('bills.index')->with('error', 'Unauthorized action.');
        }

        $categories = $this->billCategoryRepository->all(null, !$this->isAdmin ? ['house_owner_id' => $this->userId] : []);
        $flats = $this->flatRepository->all(null, !$this->isAdmin ? ['house_owner_id' => $this->userId] : []);
        $owners = $this->userRepository->allByRole('house_owner', null);

        return view('bills.edit', compact('bill', 'categories', 'flats', 'owners'));
    }

    public function update(BillEditRequest $request, int $id)
    {
        $bill = $this->billRepository->find($id);
        if (!$bill) {
            return redirect()->route('bills.index')->with('error', 'Bill not found.');
        }

        if (!$this->isAdmin && $bill->house_owner_id !== $this->userId) {
            return redirect()->route('bills.index')->with('error', 'Unauthorized action.');
        }

        $data = $request->validated();
        if (!$this->isAdmin) {
            $data['house_owner_id'] = $this->userId;
        }

        $data['month'] = Carbon::createFromFormat('Y-m', $data['month'])->format('F Y');
        try {
            $this->billRepository->update($id, $data);
        } catch (\Exception $e) {
            $this->logService->error('Error updating bill: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('bills.index')->with('error', $e->getMessage());
        }

        return redirect()->route('bills.index')->with('success', 'Bill updated successfully.');
    }

    public function destroy(int $id)
    {
        $bill = $this->billRepository->find($id);
        if (!$bill) {
            return redirect()->route('bills.index')->with('error', 'Bill not found.');
        }

        if (!$this->isAdmin && $bill->house_owner_id !== $this->userId) {
            return redirect()->route('bills.index')->with('error', 'Unauthorized action.');
        }

        try {
            $this->billRepository->delete($id);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting bill: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('bills.index')->with('error', $e->getMessage());
        }

        return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
    }

    public function filter(int $id, Request $request)
    {
        $filters['house_owner_id'] = $id;

        $categories = $this->billCategoryRepository->all(null, $filters);
        $flats = $this->flatRepository->all(null, $filters);

        return response()->json(compact('categories', 'flats'));
    }
}
