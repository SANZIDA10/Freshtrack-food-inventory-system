@extends('layouts.app')

@section('content')

<!-- HERO -->
<div class="ft-hero">
    <div class="position-relative">
        <h1>FreshTrack Inventory System</h1>
        <p>Monitor food inventory, track batches, and reduce waste.</p>

        <div class="ft-stat-grid">
            <div class="ft-stat">
                <span class="value">{{ $products->count() }}</span>
                <span class="label">Products</span>
            </div>
            <div class="ft-stat">
                <span class="value">{{ $categories->count() }}</span>
                <span class="label">Categories</span>
            </div>
            <div class="ft-stat">
                <span class="value">{{ $products->where('status', 'ACTIVE')->count() }}</span>
                <span class="label">Active</span>
            </div>
        </div>
    </div>
</div>

<!-- CATEGORIES -->
<div class="ft-section-title">Product Categories</div>
<div class="row g-3 mb-4">

    @foreach($categories as $category)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="ft-card" style="--ft-accent: {{ ['#7b3ff2', '#f8a11a', '#2ac38b', '#4f7cff', '#f14fa5', '#ff7b00'][($loop->index) % 6] }};">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h5 class="mb-1">{{ $category->category_name }}</h5>
                        <p class="mb-0">{{ $category->description ?? 'No description' }}</p>
                    </div>
                    <span class="ft-badge">{{ $category->products_count }} items</span>
                </div>

                <div class="ft-kpi">
                    <div>
                        <div class="kpi-label">Products</div>
                        <div class="kpi-value" style="color: var(--ft-accent);">{{ $category->products_count }}</div>
                    </div>
                    <div>
                        <div class="kpi-label">Avg reorder</div>
                        <div class="kpi-value">{{ number_format($category->products_avg_reorder_level ?? 0, 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>

<!-- PRODUCTS -->
<div class="ft-section-title">Latest Products</div>
<div class="row g-3">

    @foreach($products as $product)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="ft-card" style="--ft-accent: {{ ['#7b3ff2', '#f8a11a', '#2ac38b', '#4f7cff', '#f14fa5', '#ff7b00'][($loop->index) % 6] }};">
                <h5 class="mb-1">{{ $product->product_name }}</h5>
                <p class="mb-2">{{ $product->brand ?? 'N/A' }} · {{ $product->category->category_name ?? 'No Category' }}</p>

                <div class="row g-3 small text-start">
                    <div class="col-6">
                        <div class="kpi-label">Unit</div>
                        <div class="kpi-value">{{ $product->unit_of_measure }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="kpi-label">Shelf life</div>
                        <div class="kpi-value" style="color: #f08b00;">{{ $product->shelf_life_days }} days</div>
                    </div>
                    <div class="col-6">
                        <div class="kpi-label">Reorder at</div>
                        <div class="kpi-value">{{ $product->reorder_level }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="ft-badge green">{{ $product->status }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>

@endsection