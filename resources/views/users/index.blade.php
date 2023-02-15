@extends('layouts.app')

@section('content')
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <caption>users</caption>
                <tr>
                    <th class="col-4">Name</th>
                    <th class="col-4">Roles</th>
                    <th class="col-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td scope="row">{{ $user->name }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(',') }}</td>
                        <td class="d-flex">
                            <a href="/users/{{ $user->id }}/transaction" class="btn btn-outline-primary">Create transaction</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
