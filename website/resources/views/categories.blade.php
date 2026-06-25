@extends('layouts.app')

@section('content')

<div class="bg-light p-4 rounded mb-4">
    <h1>Categories</h1>
    <p class="text-muted mb-0">Review product groups and see how much inventory value each category contains.</p>
</div>

<div class="row">
    @forelse($categories as $category)
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <p class="mb-1">Products: {{ $category->products_count }}</p>
                    <p class="mb-0">Value: BDT {{ number_format($category->products_sum_price ?? 0, 2) }}</p>
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
