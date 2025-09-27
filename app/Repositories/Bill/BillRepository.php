<?php

namespace App\Repositories\Bill;

use App\Enums\BillStatuses;
use App\Enums\Roles;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BillRepository implements BillRepositoryInterface
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private Bill $billModel)
    {
        $this->isAdmin = Auth::user()->role === Roles::ADMIN;
        $this->userId = Auth::id();
    }

    public function all(?int $perPage, array $filters): LengthAwarePaginator|Collection
    {
        $query = $this->billModel->newQuery();

        // Apply filters first
        foreach ($filters as $field => $value) {
            $query->where($field, $value);
        }

        if (!$this->isAdmin && !array_key_exists('house_owner_id', $filters)) {
            $query->where('house_owner_id', $this->userId);
        }
        $query->orderBy('id', 'desc');

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }

    public function find(int $id): ?Bill
    {
        return $this->billModel
            ->when(!$this->isAdmin, fn($query) => $query->where('house_owner_id', $this->userId))
            ->whereKey($id)
            ->first();
    }

    public function create(array $data): Bill
    {
        return $this->billModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $bill = $this->find($id);
        return $bill ? $bill->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $bill = $this->find($id);
        return $bill ? $bill->delete() : false;
    }

    public function getAllDues(): LengthAwarePaginator|Collection
    {
        $query = $this->billModel->newQuery()
            ->where('status', 'unpaid');

        if (!$this->isAdmin) {
            $query->where('house_owner_id', $this->userId);
        }
        $bills = $query->orderBy('id', 'desc')->get();

        $dueBills = $bills->filter(function ($bill) {
            try {
                return \Carbon\Carbon::createFromFormat('F Y', trim($bill->month))
                    ->endOfMonth()
                    ->isPast();
            } catch (\Throwable $e) {
                return false;
            }
        });

        $dueGroups = $dueBills->groupBy('flat_id')->map(function ($group) {
            return [
                'flat_id'    => $group->first()->flat_id,
                'total_due'  => $group->sum('amount'),
                'bills'      => $group->sortByDesc('id')->values(),
            ];
        })->values();

        $perPage = config('pagination.default.per_page', 15);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paged = $dueGroups->forPage($currentPage, $perPage);

        return new LengthAwarePaginator(
            $paged,
            $dueGroups->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function getUnpaidBillsForFlat(int $flatId): Collection
    {
        $query = $this->billModel->newQuery()
            ->where('flat_id', $flatId)
            ->where('status', BillStatuses::UNPAID);

        if (!$this->isAdmin) {
            $query->where('house_owner_id', $this->userId);
        }

        return $query->orderBy('id', 'desc')->get();
    }
}
