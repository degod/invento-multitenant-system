<?php

namespace App\Http\Controllers;

use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\BillCategory\BillCategoryRepositoryInterface;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Repositories\Flat\FlatRepositoryInterface;
use App\Repositories\Tenant\TenantRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private BillRepositoryInterface $billRepository, private UserRepositoryInterface $userRepository, private BillCategoryRepositoryInterface $billCategoryRepository, private FlatRepositoryInterface $flatRepository, private TenantRepositoryInterface $tenantRepository, private BuildingRepositoryInterface $buildingRepository)
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
        $tenants = $this->tenantRepository->all(null, $filters);
        $buildings = $this->buildingRepository->all(null, $filters);
        $owners = $this->userRepository->allByRole('house_owner', null);

        return view('bills.index', compact('bills', 'categories', 'flats', 'tenants', 'buildings', 'owners'));
    }
}