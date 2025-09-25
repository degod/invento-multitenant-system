@extends('layout.master')

@section('title', 'Buildings List')

@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Buildings List</h1>

    <button class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addBuildingModal">Add New Building</button>
    <div class="modal fade" id="addBuildingModal" tabindex="-1" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBuildingModalLabel">Add New Building</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('buildings.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Building Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        @admin
                        <div class="mb-3">
                            <label for="house_owner_id" class="form-label">House Owner</label>
                            <select class="form-select" id="house_owner_id" name="house_owner_id" required>
                                <option value=""> -- Select House Owner --</option>
                                @foreach($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="house_owner_id" value="{{ Auth::user()->id }}">
                        @endadmin
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Building</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>No. of Flat</th>
                @admin
                <th>House Owner</th>
                @endadmin
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buildings as $building)
            <tr>
                <td>{{ $building->name }}</td>
                <td>{{ $building->address ?? 'N/A' }}</td>
                <td>{{ $building->flats->count() }}</td>
                @admin
                <td>{{ $building->owner->name }}</td>
                @endadmin
                <td>
                    <a href="{{ route('buildings.edit', $building->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('buildings.destroy', $building->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to DELETE this building?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No buildings found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($buildings->hasPages())
    {{ $buildings->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection