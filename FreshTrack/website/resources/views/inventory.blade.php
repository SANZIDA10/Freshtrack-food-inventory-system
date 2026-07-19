@extends('layouts.app')

@section('content')

<div class="ft-hero mb-4">
    <div class="position-relative">
        <h1>Inventory</h1>
        <p>Browse and search all food products.</p>
        <div class="ft-stat-grid" style="max-width: 18rem;">
            <div class="ft-stat">
                <span class="value">{{ $products->total() }}</span>
                <span class="label">Items</span>
            </div>
        </div>
    </div>
</div>

<div class="ft-table-panel">
    <div class="d-flex justify-content-between align-items-center px-4 pt-4 pb-2 flex-wrap gap-2">
        <div>
            <div class="ft-section-title mt-0 mb-1">All products</div>
            <p class="text-muted mb-0">Use the search bar above to filter by product, brand, or category.</p>
        </div>
        <span class="ft-badge">{{ $products->total() }} items</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Unit</th>
                    <th>Shelf Life</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category->category_name ?? '-' }}</td>
                        <td>{{ $product->brand ?? '-' }}</td>
                        <td>{{ $product->unit_of_measure }}</td>
                        <td>{{ $product->shelf_life_days }} days</td>
                        <td>{{ $product->reorder_level }}</td>
                        <td>{{ $product->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 pb-2 pt-0">
        {{ $products->withQueryString()->links() }}
    </div>
</div>

@endsection
