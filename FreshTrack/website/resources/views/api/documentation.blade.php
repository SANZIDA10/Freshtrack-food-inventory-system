@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-3">API Documentation</h2>
    <p class="text-muted">Protected endpoints for the FreshTrack inventory system.</p>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">GET /api/products</h5>
            <p class="mb-2">Returns all products with category relationships.</p>
            <p class="mb-0"><strong>Header:</strong> X-API-KEY: your-key</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">GET /api/categories</h5>
            <p class="mb-2">Returns all inventory categories.</p>
            <p class="mb-0"><strong>Header:</strong> X-API-KEY: your-key</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold">Example</h5>
            <pre class="mb-0"><code>curl -H "X-API-KEY: 47a15051e850eec0281239f8785b331e" http://127.0.0.1:8000/api/products</code></pre>
        </div>
    </div>
</div>
@endsection
