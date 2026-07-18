@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">📊 Waste & Donation Summary</h2>
            <p class="text-muted">Visualizing expired and donated food items from Oracle Analytics View.</p>
        </div>
    </div>

    <div class="row mb-5">
        <!-- চার্টের জন্য কলাম -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white fw-bold">Visual Analytics</div>
                <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 300px;">
                    <canvas id="wasteChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- টেবিল ডেটা -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white fw-bold">Data Breakdown</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Status</th>
                                    <th>Total Batches</th>
                                    <th class="pe-3">Total Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary as $row)
                                    @php
                                        // ওরাকল ভিউয়ের কলাম নাম বড় হাতের বা ভিন্ন হতে পারে, তাই ডাইনামিকালি বের করছি
                                        $rowArray = (array) $row;
                                        $statusKey = array_key_first($rowArray); // প্রথম কলামটিই সাধারণত স্ট্যাটাস হয়
                                        
                                        // যদি স্পেসিফিক কি না মেলে তবে প্রথম ভ্যালু নিবে
                                        $displayStatus = $row->status ?? $row->batch_status ?? $row->BATCH_STATUS ?? $rowArray[$statusKey] ?? 'Unknown';
                                        $totalBatches = $row->total_batches ?? $row->TOTAL_BATCHES ?? 0;
                                        $totalQuantity = $row->total_quantity ?? $row->TOTAL_QUANTITY ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-3 fw-bold">
                                            @if(strtoupper($displayStatus) == 'WASTED' || strtoupper($displayStatus) == 'EXPIRED')
                                                <span class="text-danger">🔴 {{ $displayStatus }}</span>
                                            @else
                                                <span class="text-success">🟢 {{ $displayStatus }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $totalBatches }}</td>
                                        <td class="pe-3 fw-bold">{{ $totalQuantity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No records available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN যোগ করা হচ্ছে চার্ট দেখানোর জন্য -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('wasteChart').getContext('2d');
        
        const summaryData = @json($summary);
        
        if(summaryData.length > 0) {
            // জাভাস্ক্রিপ্টেও অবজেক্টের কী ডাইনামিকালি বের করা
            const sample = summaryData[0];
            const keys = Object.keys(sample);
            const statusKey = keys[0]; 
            const quantityKey = keys.find(k => k.toLowerCase().includes('quantity')) || keys[2] || keys[1];

            const labels = summaryData.map(item => item.status || item.batch_status || item.BATCH_STATUS || item[statusKey]);
            const quantities = summaryData.map(item => item.total_quantity || item.TOTAL_QUANTITY || item[quantityKey] || 0);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Items',
                        data: quantities,
                        backgroundColor: [
                            'rgba(220, 53, 69, 0.7)',
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(255, 193, 7, 0.7)'
                        ],
                        borderColor: [
                            '#dc3545',
                            '#28a745',
                            '#ffc107'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    });
</script>
@endsection