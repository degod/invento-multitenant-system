@extends('layout.master')

@section('title', 'Bill Categories')
@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Bill Categories</h1>

    <button class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add New Category</button>
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Bill Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('bills.categories.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        @admin
                        <div class="mb-3">
                            <label for="house_owner_id" class="form-label">House Owner</label>
                            <textarea class="form-control" id="house_owner_id" name="house_owner_id" rows="3"></textarea>
                        </div>
                        @else
                        <input type="hidden" name="house_owner_id" value="N/A">
                        @endadmin
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                @admin
                <th>House Owner</th>
                @endadmin
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                @admin
                <td>{{ $category->owner->name ?? 'N/A' }}</td>
                @endadmin
                <td>
                    <a href="{{ route('bills.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('bills.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                @admin
                <td colspan="3" class="text-center">No categories found.</td>
                @else
                <td colspan="2" class="text-center">No categories found.</td>
                @endadmin
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($categories->hasPages())
    {{ $categories->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection