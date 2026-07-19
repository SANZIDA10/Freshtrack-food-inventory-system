@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">{{ $product->product_name }}</h2>
            <p class="text-muted">Brand: {{ $product->brand }} | Unit: {{ $product->unit_of_measure }}</p>
        </div>
        <a href="{{ url('/inventory') }}" class="btn btn-outline-primary">Back to Inventory</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Batch ID</th>
                            <th>Quantity</th>
                            <th>Expiry Date</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $batch->batch_id }}</td>
                                <td>{{ $batch->quantity_available ?? $batch->quantity }}</td>
                                <td>{{ $batch->expiry_date }}</td>
                                <td class="pe-4">
                                    @if(strtoupper($batch->batch_status ?? $batch->status ?? '') == 'IN_STOCK' || strtoupper($batch->batch_status ?? $batch->status ?? '') == 'ACTIVE')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Active / In Stock</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">{{ $batch->batch_status ?? $batch->status ?? 'Unknown' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No batches found for this product.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection