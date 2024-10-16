@extends('layouts/dashboard')

@section('title','Categories')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Categories</li>
@endsection


@section('content')
<div class="mb-5">
    <a href="{{route('dashboard.categories.create')}}" class="btn btn-outline-primary btn-sm">Create Category</a>
    <a href="{{route('dashboard.categories.trash')}}" class="btn btn-outline-dark btn-sm">Trashed Categories</a>
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
            <th>Number Of Products</th>
            <th>Parent</th>
            <th>Status</th>
            <th>Created at</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        
        @forelse($categories as $category)
        <tr>
            <td> <img src="{{asset('storage/' . $category->image)}}" height="50" alt=""> </td>
            <td> {{$category?->id}} </td>
            <td><a href="{{route('dashboard.categories.show',$category->id)}}">{{$category?->name}} </a> </td>
            <td> {{$category?->products_count}} </td>
            <td> {{$category?->parent->name }} </td>
            <td> {{$category?->status}} </td>
            <td> {{$category?->created_at}} </td>
            <td>
                <a href="{{route('dashboard.categories.edit',$category->id)}}" class="btn btn-sm btn-outline-success">Edit</a>

            </td>
            <td>
                <form action="{{ route('dashboard.categories.destroy',$category->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
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