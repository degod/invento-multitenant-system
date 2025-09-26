@extends('layout.master')

@section('title', 'Flats List')
@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Flats List</h1>

    <button class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addFlatModal">Add New Flat</button>
    <div class="modal fade" id="addFlatModal" tabindex="-1" aria-labelledby="addFlatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFlatModalLabel">Add New Flat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('flats.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="flat_number" class="form-label">Flat Number</label>
                            <input type="text" class="form-control" id="flat_number" name="flat_number" required>
                        </div>
                        @admin
                        <div class="mb-3">
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
                        <div class="mb-3">
                            <label for="building_id" class="form-label">Building</label>
                            <select class="form-select" id="building_id" name="building_id" required>
                                <option value=""> -- Select Building -- </option>
                                @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tenant_id" class="form-label">Tenant</label>
                            <select class="form-select" id="tenant_id" name="tenant_id" required>
                                <option value=""> -- Select Tenant -- </option>
                                @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Flat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Flat Number</th>
                <th>Owner Name</th>
                <th>Building</th>
                @admin
                <th>House Owner</th>
                @endadmin
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($flats as $flat)
            <tr>
                <td>{{ $flat->flat_number }}</td>
                <td>{{ $flat->tenant->name ?? 'N/A' }}</td>
                <td>{{ $flat->building->name }}</td>
                @admin
                <td>{{ $flat->houseOwner->name }}</td>
                @endadmin
                <td>
                    <a href="{{ route('flats.edit', $flat->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('flats.destroy', $flat->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                @admin
                <td colspan="5" class="text-center">No flats found.</td>
                @else
                <td colspan="4" class="text-center">No flats found.</td>
                @endadmin
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($flats->hasPages())
    {{ $flats->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection

@push('scripts')
<script>
    // onChange event for house_owner_id to filter buildings & tenants
    document.addEventListener("DOMContentLoaded", function() {
        const ownerSelect = document.getElementById('house_owner_id');
        if (ownerSelect) {
            ownerSelect.addEventListener('change', function() {
                const ownerId = this.value;
                const url = "{{ route('flats.filter', ':ownerId') }}".replace(':ownerId', ownerId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const buildingSelect = document.getElementById('building_id');
                        buildingSelect.innerHTML = '<option value=""> -- Select Building -- </option>';
                        data.buildings.forEach(building => {
                            const option = document.createElement('option');
                            option.value = building.id;
                            option.textContent = building.name;
                            buildingSelect.appendChild(option);
                        });

                        const tenantSelect = document.getElementById('tenant_id');
                        tenantSelect.innerHTML = '<option value=""> -- Select Tenant -- </option>';
                        data.tenants.forEach(tenant => {
                            const option = document.createElement('option');
                            option.value = tenant.id;
                            option.textContent = tenant.name;
                            tenantSelect.appendChild(option);
                        });
                    });
            });
        }
    });
</script>
@endpush