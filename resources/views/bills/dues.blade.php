@extends('layout.master')

@section('title', 'Dues Management')
@section('body_content')
<div class="container-fluid">
    <h1 class="h3">Dues Management</h1>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Flat</th>
                <th>Tenant</th>
                <th>Total Due Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dues as $due)
            <tr>
                <td>{{ $flatRepository->find($due['flat_id'])->flat_number }} (Building: {{ $flatRepository->find($due['flat_id'])->building->name }})</td>
                <td>{{ $flatRepository->find($due['flat_id'])->tenant ? $flatRepository->find($due['flat_id'])->tenant->name : 'N/A' }}</td>
                <td>${{ number_format($due['total_due'], 2) }}</td>
                <td>
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewBillModal{{ $due['flat_id'] }}">View</button>
                    <div class="modal fade" id="viewBillModal{{ $due['flat_id'] }}" tabindex="-1" aria-labelledby="viewBillModalLabel{{ $due['flat_id'] }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewBillModalLabel{{ $due['flat_id'] }}">Bill Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @foreach($due['bills'] as $item)
                                    <p><strong>Billed For:</strong> {{ $item->month }}</p>
                                    <p><strong>Bill Type:</strong> {{ $item->category->name }}</p>
                                    <p><strong>Amount:</strong> ${{ number_format($item->amount, 2) }}</p>
                                    <p><strong>Issued On:</strong> {{ $item->created_at->format('d M Y') }}</p>
                                    <p><strong>Notes:</strong> {{ $item->notes ?? 'N/A' }}</p>
                                    <hr>
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#payBillModal{{ $due['flat_id'] }}">Pay Bill</button>
                    <div class="modal fade" id="payBillModal{{ $due['flat_id'] }}" tabindex="-1" aria-labelledby="payBillModalLabel{{ $due['flat_id'] }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="payBillModalLabel{{ $due['flat_id'] }}">Pay Bill</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('dues.store') }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-muted">You are about to pay a total of <strong>${{ number_format($due['total_due'], 2) }}</strong> for this bill. This action will settle all underlying dues for this flat.</p>
                                        <input type="hidden" name="flat_id" value="{{ $due['flat_id'] }}">
                                        <input type="hidden" name="amount" value="{{ $due['total_due'] }}">
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes (make a remark if you desire)</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No dues found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($dues->hasPages())
    {{ $dues->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection