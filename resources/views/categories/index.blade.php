@extends('layouts.app')

@section('content')
<p>
    <a href="/categories/create" class="btn btn-primary">Create</a>
</p>
<div class="table-responsive">
    <table
        class="table table-hover table-bordered align-middle">
        <thead class="table-light">
            <caption>Categories</caption>
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
                        <a href="/categories/{{ $category->id }}/edit" class="btn btn-outline-primary">Edit</a> &nbsp; |
                        &nbsp;
                        <form action="{{ url('/categories/' . $category->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete {{$category->name}} category?')" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
