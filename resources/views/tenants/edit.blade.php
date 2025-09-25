@extends('layout.master')

@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Tenant</h1>

    <div class="row">
        <div class="col-lg-6">
            <form action="{{ route('tenants.update', $tenant->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $tenant->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $tenant->email }}" required>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="{{ $tenant->contact }}" required>
                </div>
                <div class="mb-3">
                    <label for="house_owner_id" class="form-label">Owner</label>
                    <select class="form-select" id="house_owner_id" name="house_owner_id" required>
                        <option value="">-- Select House Owner --</option>
                        @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" {{ $tenant->house_owner_id == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Tenant</button>
            </form>
        </div>
        <div class="col-lg-6"></div>
    </div>
</div>
@endsection