@extends('layouts.app')

@section('content')
    <h3>Edit contact type</h3>
    <form action="/contactInfoTypes/{{ $contactInfoType->id }}" method="post" class="col-md-6 needs-validation"
        novalidate>
        @method('PUT')
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Category name" required
                value="{{ $contactInfoType->name }}">
            @error('name')
                <p class="text-red text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control" name="url" id="url" placeholder="https://www.facebook.com/"
                required value="{{ $contactInfoType->url }}">
            @error('url')
                <p class="text-red text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="3">{{ $contactInfoType->description }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Edit</button>
    </form>
    <hr>
    <p>
        <a href="{{ url('/contactInfoTypes') }}" class="link">Back to list</a>
    </p>
@endsection

@section('scripts')
    <x-validation-scripts></x-validation-scripts>
@endsection
