@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">📜 Purchase History</h2>
            <p class="text-muted">Overview of all inventory supply orders and purchase records.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Purchase ID</th>
                            <th>Supplier Name</th>
                            <th>Order Date</th>
                            <th class="pe-4">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $purchase->purchase_id }}</td>
                                <td class="fw-semibold text-dark">{{ $purchase->supplier_name }}</td>
                                <td>{{ $purchase->purchase_date }}</td>
                                <td class="pe-4 fw-bold text-primary">${{ number_format($purchase->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No purchase records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection