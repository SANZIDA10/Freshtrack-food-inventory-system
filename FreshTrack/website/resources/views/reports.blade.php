@extends('layouts.app')

@section('content')

<div class="ft-hero mb-4">
    <div class="position-relative">
        <h1>Reports</h1>
        <p>Inventory totals, category performance, and recent products.</p>
        <div class="ft-stat-grid">
            <div class="ft-stat">
                <span class="value">{{ $totalCategories }}</span>
                <span class="label">Categories</span>
            </div>
            <div class="ft-stat">
                <span class="value">{{ $totalProducts }}</span>
                <span class="label">Products</span>
            </div>
            <div class="ft-stat">
                <span class="value">{{ $activeProducts }}</span>
                <span class="label">Active</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="ft-panel">
            <p class="kpi-label mb-1">Total Categories</p>
            <h2 class="mb-0" style="color: var(--ft-purple);">{{ $totalCategories }}</h2>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="ft-panel">
            <p class="kpi-label mb-1">Total Products</p>
            <h2 class="mb-0">{{ $totalProducts }}</h2>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="ft-panel">
            <p class="kpi-label mb-1">Active Products</p>
            <h2 class="mb-0" style="color: var(--ft-green);">{{ $activeProducts }}</h2>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="ft-panel">
            <p class="kpi-label mb-1">Average Shelf Life</p>
            <h2 class="mb-0" style="color: #f08b00;">{{ number_format($averageShelfLife ?? 0, 0) }} days</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="ft-table-panel mb-4">
            <div class="px-4 pt-4 pb-2">
                <div class="ft-section-title mt-0 mb-1">Category Breakdown</div>
                <p class="text-muted mb-0">Product counts and average reorder levels by category.</p>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Products</th>
                            <th>Avg Reorder Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryBreakdown as $category)
                            <tr>
                                <td>{{ $category->category_name }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td>{{ number_format($category->products_avg_reorder_level ?? 0, 0) }}</td>
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
        <div class="ft-list-panel mb-4">
            <div class="px-4 pt-4 pb-2">
                <div class="ft-section-title mt-0 mb-1">Recently Added</div>
                <p class="text-muted mb-0">Latest products in the catalog.</p>
            </div>
            <div class="list-group list-group-flush">
                @forelse($latestProducts as $product)
                    <div class="list-group-item ft-list-item">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $product->product_name }}</strong>
                            <span class="ft-badge pink">{{ $product->category->category_name ?? 'No Category' }}</span>
                        </div>
                        <small>{{ $product->brand ?? 'No Brand' }} · {{ $product->unit_of_measure }} · {{ $product->status }}</small>
                    </div>
                @empty
                    <div class="list-group-item text-center p-4">No products have been added yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
