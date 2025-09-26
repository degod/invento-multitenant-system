@extends('layout.master')

@section('title', 'Edit Category')
@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Category</h1>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('bills.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                </div>
                @admin
                <div class="mb-3">
                    <label for="house_owner_id" class="form-label">House Owner</label>
                    <select class="form-select" id="house_owner_id" name="house_owner_id">
                        <option value="">Select House Owner</option>
                        @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" {{ $category->house_owner_id == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="house_owner_id" value="{{ $category->house_owner_id ?? Auth::user()->id }}">
                @endadmin
                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </div>
        <div class="col-md-6"></div>
    </div>
</div>
@endsection