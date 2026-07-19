@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Edit Product</h2>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $category->category_id == $product->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Unit of Measure</label>
                <input type="text" name="unit_of_measure" class="form-control" value="{{ $product->unit_of_measure }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Shelf Life (days)</label>
                <input type="number" name="shelf_life_days" class="form-control" min="1" value="{{ $product->shelf_life_days }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Reorder Level</label>
                <input type="number" name="reorder_level" class="form-control" min="0" value="{{ $product->reorder_level }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="ACTIVE" {{ $product->status == 'ACTIVE' ? 'selected' : '' }}>ACTIVE</option>
                    <option value="INACTIVE" {{ $product->status == 'INACTIVE' ? 'selected' : '' }}>INACTIVE</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imageInputEdit" onchange="previewImage(this, 'imagePreviewEdit')">
                @if(!empty($product->image_path))
                    <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid rounded mt-3" style="max-height: 180px;">
                @endif
                <img id="imagePreviewEdit" class="img-fluid rounded mt-3" style="max-height: 180px; display: none;">
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endsection
