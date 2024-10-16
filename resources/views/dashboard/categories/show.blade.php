@extends('layouts/dashboard')

@section('title',$category->name)
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Categories</li>
<li class="breadcrumb-item active">{{$category->name}}</li>
@endsection


@section('content')
<div class="mb-5">
    <a href="{{route('dashboard.products.create')}}" class="btn btn-outline-primary btn-sm">Create Product</a>
    <!-- <a href="{{-- route('dashboard.products.trash') --}}" class="btn btn-outline-dark btn-sm">Trashed Products</a> -->
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
            <th>Category</th>
            <th>Store</th>
            <th>Status</th>
            <th>Created at</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
      @php
        $products = $category->products()->with('store')->paginate(5);
      @endphp
        @forelse($products as $product)
        <tr>
            <td> <img src="{{asset('storage/' . $product->image)}}" height="50" alt=""> </td>
            <td> {{$product?->id}} </td>
            <td> {{$product?->name}} </td>
            <td> {{$product?->category->name}} </td>
            <td> {{$product?->store->name}} </td>
            <td> {{$product?->status}} </td>
            <td> {{$product?->created_at}} </td>
            <td>
                <a href="{{route('dashboard.products.edit',$product->id)}}" class="btn btn-sm btn-outline-success">Edit</a>

            </td>
            <td>
                <form action="{{ route('dashboard.products.destroy',$product->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No products</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $products->withQueryString()->links()}}

@endsection