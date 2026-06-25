@extends('layouts.app')

@section('content')

<div class="bg-light p-4 rounded mb-4">
    <h1>Reports</h1>
    <p class="text-muted mb-0">Track inventory totals, category performance, and recently added products.</p>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Total Categories</p>
                <h2 class="mb-0">{{ $totalCategories }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Total Products</p>
                <h2 class="mb-0">{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Inventory Value</p>
                <h2 class="mb-0">BDT {{ number_format($totalValue, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header">Category Breakdown</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Products</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryBreakdown as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td>BDT {{ number_format($category->products_sum_price ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center p-4">No category data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header">Recently Added Products</div>
            <div class="list-group list-group-flush">
                @forelse($latestProducts as $product)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $product->name }}</strong>
                            <span>BDT {{ number_format($product->price, 2) }}</span>
                        </div>
                        <small class="text-muted">{{ $product->category->name ?? 'No Category' }}</small>
                    </div>
                @empty
                    <div class="list-group-item text-center p-4">No products have been added yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
