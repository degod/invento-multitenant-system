<?php

namespace App\Http\Controllers;

use App\Enums\BillStatuses;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Flat\FlatRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\EmailService;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DueController extends Controller
{
    private bool $isAdmin;
    private int $userId;

    public function __construct(private BillRepositoryInterface $billRepository, private FlatRepositoryInterface $flatRepository, private UserRepositoryInterface $userRepository, private LogService $logService, private EmailService $emailService)
    {
        $this->isAdmin = Auth::user()->role === 'admin';
        $this->userId = Auth::id();
    }

    public function index()
    {
        $dues = $this->billRepository->getAllDues();
        $flatRepository = $this->flatRepository;

        return view('bills.dues', compact('dues', 'flatRepository'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flat_id' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            $bills = $this->billRepository->getUnpaidBillsForFlat($validated['flat_id']);
            foreach ($bills as $bill) {
                $this->billRepository->update($bill->id, [
                    'status' => BillStatuses::PAID,
                    'notes' => $validated['notes'],
                ]);
            }
            $this->logService->info('Cleared dues for flat ' . $bill->flat->flat_number, [
                'action_user_id' => $this->userId,
                'house_owner_id' => $bill->house_owner_id,
                'flat_id' => $bill->flat_id,
                'amount' => $bill->amount,
            ]);
            $tenant = $bill->flat->tenant;
            if ($tenant) {
                $this->emailService->sendDueNotification($tenant->email, $bills);
            }
        } catch (\Exception $e) {
            $this->logService->error('Error clearing due: ' . $e->getMessage(), ['action_user_id' => $this->userId]);
            return redirect()->back()->with('error', 'An error occurred while clearing the due.');
        }

        return redirect()->route('dues.index')->with('success', 'Due cleared successfully.');
    }
}
