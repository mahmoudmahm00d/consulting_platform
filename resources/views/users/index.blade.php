@extends('layouts.app')

@section('content')
    <x-datatable>
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Roles</th>
                <th class="ms-auto">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td scope="row">{{ $user->name }}</td>
                    <td>{{ $user->roles->pluck('name')->implode(',') }}</td>
                    <td class="d-flex">
                        <a href="/users/{{ $user->id }}/transaction" class="ms-auto btn btn-link btn-sm">Create
                            transaction</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-datatable>
@endsection
