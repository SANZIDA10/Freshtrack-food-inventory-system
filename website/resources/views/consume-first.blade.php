@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">🍏 Consume First Recommendations</h2>
            <p class="text-muted">Smart priority list based on Oracle Analytics View to reduce food waste.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Priority</th>
                            <th>Product Name</th>
                            <th>Batch Code</th>
                            <th>Quantity Available</th>
                            <th>Expiry Date</th>
                            <th class="pe-4">Recommendation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recommendations as $index => $row)
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <span class="badge bg-danger rounded-circle p-2" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="fw-bold text-dark">{{ $row->product_name ?? 'Unknown Product' }}</td>
                                <td><code>{{ $row->batch_code ?? 'N/A' }}</code></td>
                                <td class="fw-bold text-warning-dominant">{{ $row->quantity_available ?? $row->quantity ?? 0 }}</td>
                                <td class="text-danger fw-medium">{{ $row->expiry_date }}</td>
                                <td class="pe-4">
                                    <span class="text-success fw-semibold">
                                        ✨ Use this batch first!
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <p class="mb-0">No active recommendations. Everything looks perfectly fresh!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection