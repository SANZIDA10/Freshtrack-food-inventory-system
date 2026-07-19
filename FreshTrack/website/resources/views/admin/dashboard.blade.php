@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Admin Dashboard</h2>
            <p class="text-muted mb-0">Overview of the inventory system.</p>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Products</h6>
                    <h2 class="fw-bold">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Categories</h6>
                    <h2 class="fw-bold">{{ $totalCategories }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Active Products</h6>
                    <h2 class="fw-bold">{{ $activeProducts }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Manage Products</a>
    </div>
</div>
@endsection
