@extends('layouts.app')

@section('content')
<p>
    <a href="/contactInfoTypes/create" class="btn btn-primary">Create</a>
</p>
<div class="table-responsive">
    <table
        class="table table-hover table-bordered align-middle">
        <thead class="table-light">
            <caption>Contact info types</caption>
            <tr>
                <th>Name</th>
                <th>URL</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contactInfoTypes as $contactInfo)
                <tr>
                    <td scope="row">{{ $contactInfo->name }}</td>
                    <td>{{ $contactInfo->url }}</td>    
                    <td class="text-truncate">{{ $contactInfo->description }}</td>
                    <td class="d-flex">
                        <a href="/contactInfoTypes/{{ $contactInfo->id }}/edit" class="btn btn-outline-primary">Edit</a> &nbsp; |
                        &nbsp;
                        <form action="{{ url('/contactInfoTypes/' . $contactInfo->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete {{$contactInfo->name}} contactInfo?')" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
