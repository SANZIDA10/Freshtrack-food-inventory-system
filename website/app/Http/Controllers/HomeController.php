<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        $products = Product::with('category')->latest()->take(6)->get();

        return view('home', compact('categories', 'products'));
    }
}