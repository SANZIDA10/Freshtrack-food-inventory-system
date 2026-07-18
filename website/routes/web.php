<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

Route::get('/', function () {

    $categories = Category::withCount('products')
        ->orderBy('category_name')
        ->get();

    $products = Product::with('category')
                ->orderByDesc('product_id')
                ->take(6)
                ->get();

    return view('home', compact(
        'categories',
        'products'
    ));
});

Route::get('/inventory', function (Request $request) {
    $q = $request->get('q');

    $products = Product::with('category')
        ->when($q, function ($query, $q) {
            $query->where('product_name', 'like', "%{$q}%")
                  ->orWhere('brand', 'like', "%{$q}%")
                  ->orWhereHas('category', function ($q2) use ($q) {
                      $q2->where('category_name', 'like', "%{$q}%");
                  });
        })
        ->orderByDesc('product_id')
        ->paginate(12);

    return view('inventory', compact('products', 'q'));
});

Route::get('/categories', function () {
    $categories = Category::withCount('products')
        ->withAvg('products', 'reorder_level')
        ->orderBy('category_name')
        ->get();

    return view('categories', compact('categories'));
});

Route::get('/reports', function () {
    $totalCategories = Category::count();
    $totalProducts = Product::count();
    $activeProducts = Product::where('status', 'ACTIVE')->count();
    $averageShelfLife = Product::avg('shelf_life_days');

    $categoryBreakdown = Category::withCount('products')
        ->withAvg('products', 'reorder_level')
        ->orderByDesc('products_count')
        ->get();

    $latestProducts = Product::with('category')
        ->orderByDesc('product_id')
        ->take(5)
        ->get();

    return view('reports', compact(
        'totalCategories',
        'totalProducts',
        'activeProducts',
        'averageShelfLife',
        'categoryBreakdown',
        'latestProducts'
    ));
});
