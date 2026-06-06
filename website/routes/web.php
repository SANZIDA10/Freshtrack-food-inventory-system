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