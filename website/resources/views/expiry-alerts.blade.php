@extends('layouts.app')
@section('content')

<div class="ft-hero" style="background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);">
    <h1>⚠ Expiry Alerts</h1>
    <p>Batches expiring within 30 days and already expired items.</p>
    <div class="ft-stat-grid">
        <div class="ft-stat">
            <span class="value">{{ count($expiring) }}</span>
            <span class="label">Expiring Soon</span>
        </div>
        <div class="ft-stat">
            <span class="value">{{ count($expired) }}</span>
            <span class="label">Already Expired</span>
        </div>
    </div>
</div>

{{-- EXPIRING SOON --}}
<p class="ft-section-title">⏳ Expiring Within 30 Days</p>
<div class="ft-table-panel mb-4">
    <table class="table">
        <thead>
            <tr>
                <th>Batch Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Expiry Date</th>
                <th>Days Left</th>
                <th>Qty Available</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expiring as $row)
            <tr>
                <td><strong>{{ $row->batch_code }}</strong></td>
                <td>{{ $row->product_name }}</td>
                <td><span class="ft-badge">{{ $row->category_name }}</span></td>
                <td>{{ \Carbon\Carbon::parse($row->expiry_date)->format('d M Y') }}</td>
                <td>
                    @if($row->days_until_expiry <= 3)
                        <span class="ft-badge red">{{ $row->days_until_expiry }} days</span>
                    @elseif($row->days_until_expiry <= 7)
                        <span class="ft-badge orange">{{ $row->days_until_expiry }} days</span>
                    @else
                        <span class="ft-badge yellow">{{ $row->days_until_expiry }} days</span>
                    @endif
                </td>
                <td>{{ $row->quantity_available }}</td>
                <td><span class="ft-badge green">{{ $row->batch_status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No batches expiring within 30 days.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ALREADY EXPIRED --}}
<p class="ft-section-title">🚨 Already Expired</p>
<div class="ft-table-panel mb-4">
    <table class="table">
        <thead>
            <tr>
                <th>Batch Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Expiry Date</th>
                <th>Qty Available</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expired as $row)
            <tr>
                <td><strong>{{ $row->batch_code }}</strong></td>
                <td>{{ $row->product_name }}</td>
                <td><span class="ft-badge pink">{{ $row->category_name }}</span></td>
                <td><span class="ft-badge red">{{ \Carbon\Carbon::parse($row->expiry_date)->format('d M Y') }}</span></td>
                <td>{{ $row->quantity_available }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">No expired batches found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection