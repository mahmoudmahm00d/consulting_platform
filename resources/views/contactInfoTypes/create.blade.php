@extends('layouts.app')

@section('content')
    <h3>Create contact type</h3>
    <form action="/contactInfoTypes" method="post" enctype="multipart/form-data" class="col-md-6 needs-validation card p-5" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control form-control-solid" name="name" id="name" placeholder="Facebook Account" required>
            @error('name')
                <p class="text-red text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control form-control-solid" name="url" id="url" placeholder="https://www.facebook.com/" required>
            @error('url')
                <p class="text-red text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control form-control-solid" name="description" id="description" rows="3"></textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    <p class="mt-3">
        <a href="{{url('/contactInfoTypes')}}" class="btn btn-link">Back to contact types</a>
    </p>
@endsection

@section('scripts')
<x-validation-scripts></x-validation-scripts>
@endsection
