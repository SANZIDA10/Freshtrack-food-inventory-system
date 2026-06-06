@extends('layouts.app')

@section('content')

<!-- HERO -->
<div class="bg-primary text-white text-center p-5 mb-4">
    <h1>FreshTrack Inventory System</h1>
    <p>Manage Categories & Products Easily</p>
</div>

<!-- CATEGORIES -->
<h3>Categories</h3>
<div class="row mb-5">

    @foreach($categories as $category)
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5>{{ $category->name }}</h5>
                    <p>Total Products: {{ $category->products->count() }}</p>
                </div>
            </div>
        </div>
    @endforeach

</div>

<!-- PRODUCTS -->
<h3>Latest Products</h3>
<div class="row">

    @foreach($products as $product)
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5>{{ $product->name }}</h5>
                    <p>Price: ৳{{ $product->price }}</p>
                    <span class="badge bg-info">
                        {{ $product->category->name ?? 'No Category' }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach

</div>

@endsection