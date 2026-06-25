<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

Route::get('/', function () {

    $categories = Category::with('products')->get();

    $products = Product::with('category')
                ->latest()
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
            $query->where('name', 'like', "%{$q}%")
                  ->orWhereHas('category', function ($q2) use ($q) {
                      $q2->where('name', 'like', "%{$q}%");
                  });
        })
        ->latest()
        ->paginate(12);

    return view('inventory', compact('products', 'q'));
});

Route::get('/categories', function () {
    $categories = Category::withCount('products')
        ->withSum('products', 'price')
        ->orderBy('name')
        ->get();

    return view('categories', compact('categories'));
});

Route::get('/reports', function () {
    $totalCategories = Category::count();
    $totalProducts = Product::count();
    $totalValue = Product::sum('price');

    $categoryBreakdown = Category::withCount('products')
        ->withSum('products', 'price')
        ->orderByDesc('products_count')
        ->get();

    $latestProducts = Product::with('category')
        ->latest()
        ->take(5)
        ->get();

    return view('reports', compact(
        'totalCategories',
        'totalProducts',
        'totalValue',
        'categoryBreakdown',
        'latestProducts'
    ));
});
