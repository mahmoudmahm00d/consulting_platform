@extends('layouts.app')

@section('content')
    <h3>Create Transaction</h3>
    <form action="/users/{{ $id }}/deposit" method="post" class="col-md-6 needs-validation" novalidate>
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="text" class="form-control" name="amount" id="amount" placeholder="100" required>
            @error('name')
                <p class="text-red text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Deposit</button>
    </form>
    <p class="mt-3">
        <a href="{{ url('/users') }}" class="btn btn-link">Back to users</a>
    </p>
@endsection

@section('scripts')
    <x-validation-scripts></x-validation-scripts>
@endsection
