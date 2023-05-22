@extends('layouts.app')

@section('content')
    <h3>Create category</h3>
    <form action="/categories" method="post" enctype="multipart/form-data" class="col-md-6 needs-validation card p-5" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control form-control-solid" name="name" id="name" placeholder="Category name" required>
            @error('name')
                <p class="text-red text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="sequence" class="form-label">Sequence</label>
            <input type="number" class="form-control form-control-solid" name="sequence" id="sequence" placeholder="Sequance" required>
            @error('sequence')
                <p class="text-red text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control form-control-solid" accept=".jpg, .png" name="image" id="image"/>
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    <p class="mt-3">
        <a href="{{url('/categories')}}" class="btn btn-link">Back to categories</a>
    </p>
@endsection

@section('scripts')
<x-validation-scripts></x-validation-scripts>
@endsection
