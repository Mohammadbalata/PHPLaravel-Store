@extends('layouts/dashboard')

@section('title','Trashed Categories')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Categories</li>
<li class="breadcrumb-item active">Trash</li>
@endsection


@section('content')
<div class="mb-5">
    <a href="{{route('dashboard.categories.index')}}" class="btn btn-outline-primary btn-sm">Back</a>
</div>

<x-alert type="success" />
<x-alert type="info" />


<form action="{{URL::current()}}" method="get" class="d-flex justify-content-between gap-4 mb-4">
    <x-form.input name="name" placeholder="Name"  class="mx-2" :value="request('name')" />
    <select name="status" class="form-control form-select mx-2">
        <option value="">All</option>
        <option value="active" @selected(request('status') == 'active')>Active</option>
        <option value="archived" @selected(request('status') == 'archived')>Archived</option>
    </select>

    <button type="submit" class="btn btn-dark mx-2">Search</button>
</form>
<table class="table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Id</th>
            <th>Name</th>
            <th>Parent</th>
            <th>Status</th>

            <th>Deleted at</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td> <img src="{{asset('storage/' . $category->image)}}" height="50" alt=""> </td>
            <td> {{$category?->id}} </td>
            <td> {{$category?->name}} </td>
            <td> {{$category?->parent_name ?? 'none'}} </td>
            <td> {{$category?->status}} </td>
            <td> {{$category?->deleted_at}} </td>
            <td>
            <form action="{{ route('dashboard.categories.restore',$category->id) }}" method="post">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-outline-info btn-sm">Restore</button>
                </form>
                

            </td>
            <td>
                <form action="{{ route('dashboard.categories.force-delete',$category->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Force Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No Categories</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $categories->withQueryString()->links()}}

@endsection