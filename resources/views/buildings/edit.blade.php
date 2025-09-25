@extends('layout.master')

@section('title', 'Edit Building')
@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Building</h1>
    <form action="{{ route('buildings.update', $building->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Building Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $building->name }}" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $building->address }}">
        </div>
        @admin
        <div class="mb-3">
            <label for="house_owner_id" class="form-label">House Owner</label>
            <select class="form-select" id="house_owner_id" name="house_owner_id" required>
                <option value=""> -- Select House Owner --</option>
                @foreach($owners as $owner)
                <option value="{{ $owner->id }}" {{ $building->house_owner_id == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                @endforeach
            </select>
        </div>
        @endadmin
        <button type="submit" class="btn btn-primary">Update Building</button>
    </form>
</div>
@endsection