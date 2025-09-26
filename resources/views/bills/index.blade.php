<?php

use App\Enums\BillStatuses;
?>
@extends('layout.master')

@section('title', 'Bills List')

@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Bills List</h1>

    <button class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addBillModal">Add New Bill</button>
    <div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBillModalLabel">Add New Bill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('bills.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="status" value="{{ BillStatuses::UNPAID }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="month" class="form-label">Month (with year)</label>
                                <input type="month" class="form-control" id="month" name="month" required>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                            @admin
                            <div class="col-md-6">
                                <label for="house_owner_id" class="form-label">House Owner</label>
                                <select class="form-select" id="house_owner_id" name="house_owner_id" required>
                                    <option value=""> -- Select House Owner -- </option>
                                    @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <input type="hidden" name="house_owner_id" value="{{ Auth::user()->id }}">
                            @endadmin
                            <div class="col-md-6">
                                <label for="description" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="bill_category_id" required>
                                    <option value=""> -- Select Category -- </option>
                                    @admin
                                    @else
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                    @endadmin
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="flat_id" class="form-label">Flat</label>
                                <select class="form-select" id="flat_id" name="flat_id" required>
                                    <option value=""> -- Select Flat -- </option>
                                    @admin
                                    @else
                                    @foreach($flats as $flat)
                                    <option value="{{ $flat->id }}">{{ $flat->flat_number }} - {{ $flat->tenant->name }}</option>
                                    @endforeach
                                    @endadmin
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="notes" class="form-label">Notes (optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Month</th>
                <th>Bill Type</th>
                <th>Amount</th>
                <th>Status</th>
                @admin
                <th>House Owner</th>
                @endadmin
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bills as $bill)
            <tr>
                <td>{{ $bill->month }}</td>
                <td>{{ $bill->category->name }}</td>
                <td>{{ '$' . number_format($bill->amount, 2, '.', ',') }}</td>
                <td>
                    <span class="badge bg-{{ $bill->status === BillStatuses::PAID ? 'success' : 'danger' }}">{{ $bill->status }}</span>
                </td>
                <td>{{ $bill->owner->name }}</td>
                <td>
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewBillModal{{ $bill->id }}">View</button>
                    <div class="modal fade" id="viewBillModal{{ $bill->id }}" tabindex="-1" aria-labelledby="viewBillModalLabel{{ $bill->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewBillModalLabel{{ $bill->id }}">Bill Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Month's Bill:</strong> {{ $bill->month }}</p>
                                    <p><strong>Bill Type:</strong> {{ $bill->category->name }}</p>
                                    <p><strong>Amount:</strong> {{ '$' . number_format($bill->amount, 2, '.', ',') }}</p>
                                    <p><strong>Status:</strong> {{ $bill->status }}</p>
                                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($bill->created_at)->format('F jS, Y g:s a') }}</p>
                                    <p><strong>Notes (optional):</strong> {{ $bill->notes ?? 'N/A' }}</p>
                                    <hr />
                                    <p><strong>House owner:</strong> {{ $bill->owner->name }}</p>
                                    <p><strong>Tenant:</strong> {{ $bill->flat->tenant->name }}</p>
                                    <p><strong>Flat:</strong> {{ $bill->flat->flat_number }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('bills.destroy', $bill->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to DELETE this bill?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                @admin
                <td colspan="6" class="text-center">No bills found.</td>
                @else
                <td colspan="5" class="text-center">No bills found.</td>
                @endadmin
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($bills->hasPages())
    {{ $bills->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection

@push('scripts')
<script>
    // onChange event for house_owner_id to filter categories & flats
    document.addEventListener("DOMContentLoaded", function() {
        const ownerSelect = document.getElementById('house_owner_id');
        if (ownerSelect) {
            ownerSelect.addEventListener('change', function() {
                const ownerId = this.value;
                const url = "{{ route('bills.filter', ':ownerId') }}".replace(':ownerId', ownerId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const categorySelect = document.getElementById('category_id');
                        categorySelect.innerHTML = '<option value=""> -- Select Category -- </option>';
                        data.categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });

                        const flatSelect = document.getElementById('flat_id');
                        flatSelect.innerHTML = '<option value=""> -- Select Flat -- </option>';
                        data.flats.forEach(flat => {
                            const option = document.createElement('option');
                            option.value = flat.id;
                            option.textContent = flat.flat_number;
                            flatSelect.appendChild(option);
                        });
                    });
            });
        }
    });
</script>
@endpush