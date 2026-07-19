@extends('layouts.app')

@section('content')

<div class="ft-hero mb-4">
    <div class="position-relative">
        <h1>Categories</h1>
        <p>All food product groups and item counts.</p>
        <div class="ft-stat-grid" style="max-width: 18rem;">
            <div class="ft-stat">
                <span class="value">{{ $categories->count() }}</span>
                <span class="label">Categories</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    @forelse($categories as $category)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="ft-card" style="--ft-accent: {{ ['#7b3ff2', '#f8a11a', '#2ac38b', '#4f7cff', '#f14fa5', '#ff7b00'][($loop->index) % 6] }};">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $category->category_name }}</h5>
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
    @empty
        <div class="col-12">
            <div class="alert alert-info">No categories have been added yet.</div>
        </div>
    @endforelse
</div>

@endsection
