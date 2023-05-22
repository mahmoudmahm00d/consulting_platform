@extends('layouts.app')

@section('content')
    <p>
        <a href="/categories/create" class="btn btn-primary btn-sm rounded-2">Create</a>
    </p>
    <x-datatable>
        <thead class="table-light">
            <tr>
                <th class="col-8">Name</th>
                <th class="col-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td scope="row">{{ $category->name }}</td>
                    <td class="d-flex">
                        <a href="/categories/{{ $category->id }}/edit" class="btn btn-link btn-sm rounded-2">Edit</a>
                        &nbsp;&nbsp;
                        <form action="{{ url('/categories/' . $category->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete {{ $category->name }} category?')"
                                class="btn btn-link text-danger btn-sm rounded-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-datatable>
@endsection
