@extends('layout.master')

@section('title', 'Flat Edit')
@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Flat Edit</h1>

    <form action="{{ route('flats.update', $flat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="flat_number" class="form-label">Flat Number</label>
            <input type="text" class="form-control" id="flat_number" name="flat_number" value="{{ $flat->flat_number }}" required>
        </div>
        @admin
        <div class="mb-3">
            <label for="house_owner_id" class="form-label">House Owner</label>
            <select class="form-select" id="house_owner_id" name="house_owner_id" required>
                <option value=""> -- Select House Owner -- </option>
                @foreach($owners as $owner)
                <option value="{{ $owner->id }}" {{ $owner->id == $flat->house_owner_id ? 'selected' : '' }}>{{ $owner->name }}</option>
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
                <option value="{{ $building->id }}" {{ $building->id == $flat->building_id ? 'selected' : '' }}>{{ $building->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tenant_id" class="form-label">Tenant</label>
            <select class="form-select" id="tenant_id" name="tenant_id" required>
                <option value=""> -- Select Tenant -- </option>
                @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}" {{ $tenant->id == $flat->tenant_id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update Flat</button>
        </div>
    </form>
</div>
@endsection