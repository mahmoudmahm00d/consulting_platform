@extends('layouts.app')

@section('styles')
    <link href="libs/keentheme/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="libs/keentheme/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <p>
        <a href="/contactInfoTypes/create" class="btn btn-sm btn-primary rounded-2">Create new type</a>
    </p>
    <x-datatable>
        <thead class="table-light">
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
                        <a href="/contactInfoTypes/{{ $contactInfo->id }}/edit" class="btn btn-link btn-sm rounded-2">Edit</a>&nbsp;&nbsp;
                        <form action="{{ url('/contactInfoTypes/' . $contactInfo->id) }}" method="post" class="ps-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete {{ $contactInfo->name }} contactInfo?')"
                                class="btn btn-sm btn-link text-danger rounded-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-datatable>
@endsection
