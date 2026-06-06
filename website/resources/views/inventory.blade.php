@extends('layouts.app')

@section('content')

<div class="bg-light p-4 rounded mb-4">
    <h1>Inventory</h1>
    <p class="text-muted">Browse and search products. Use the search box in the navbar to filter by product name or category.</p>
</div>

<div class="card mb-4">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>৳{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $products->withQueryString()->links() }}
    </div>
</div>

@endsection
