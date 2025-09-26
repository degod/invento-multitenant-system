@extends('layout.master')

@section('title', 'Edit Bill')
@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Bill</h1>
    <form action="{{ route('bills.update', $bill->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label for="month" class="form-label">Month (with year)</label>
                <input type="month" class="form-control" id="month" name="month"
                    value="{{ \Carbon\Carbon::createFromFormat('F Y', $bill->month)->format('Y-m') }}"
                    required>
            </div>
            <div class="col-md-6">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $bill->amount }}" required>
            </div>

            <input type="hidden" name="house_owner_id" value="{{ $bill->house_owner_id }}">

            <div class="col-md-6">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="bill_category_id" required>
                    <option value=""> -- Select Category -- </option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $bill->bill_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="flat_id" class="form-label">Flat</label>
                <select class="form-select" id="flat_id" name="flat_id" required>
                    <option value=""> -- Select Flat -- </option>
                    @foreach($flats as $flat)
                    <option value="{{ $flat->id }}" {{ $bill->flat_id == $flat->id ? 'selected' : '' }}>{{ $flat->flat_number }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="unpaid" {{ $bill->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ $bill->status == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="notes" class="form-label">Notes (optional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ $bill->notes }}</textarea>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-success">Update Bill</button>
        </div>
    </form>
</div>
@endsection