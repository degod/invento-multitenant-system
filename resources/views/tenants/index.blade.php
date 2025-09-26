@extends('layout.master')

@section('title', 'Tenants List')
@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Tenants List</h1>

    @admin
    <button class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addTenantModal">Add Tenant</button>
    <div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="addTenantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTenantModalLabel">Add New Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tenants.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="contact" name="contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="house_owner_id" class="form-label">Owner</label>
                            <select class="form-select" id="house_owner_id" name="house_owner_id" required>
                                <option value="">-- Select House Owner --</option>
                                @foreach($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Tenant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endadmin

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                @admin
                <th>Owner</th>
                @endadmin
                <th>Flat</th>
                @admin
                <th>Actions</th>
                @endadmin
            </tr>
        </thead>
        <tbody>
            @forelse($tenants as $tenant)
            <tr>
                <td>{{ $tenant->name }}</td>
                <td>{{ $tenant->email }}</td>
                <td>{{ $tenant->contact }}</td>
                @admin
                <td>{{ $tenant->owner->name }}</td>
                @endadmin
                <td>{{ $tenant->flat->flat_number ?? 'N/A' }}</td>
                @admin
                <td>
                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to DELETE this tenant?')">
                            Delete
                        </button>
                    </form>
                </td>
                @endadmin
            </tr>
            @empty
            <tr>
                @admin
                <td colspan="6" class="text-center">No tenants found.</td>
                @else
                <td colspan="4" class="text-center">No tenants found.</td>
                @endadmin
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tenants->hasPages())
    {{ $tenants->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection