<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $products = Product::with('category')->latest()->take(6)->get();
        return view('home', compact('categories', 'products'));
    }

    public function inventory(Request $request)
    {
        $q = $request->get('q');
        $query = Product::with('category');

        if ($q) {
            $query->whereRaw('UPPER(product_name) LIKE UPPER(?)', ["%{$q}%"])
                  ->orWhereRaw('UPPER(brand) LIKE UPPER(?)', ["%{$q}%"])
                  ->orWhereHas('category', function($cat) use ($q) {
                      $cat->whereRaw('UPPER(category_name) LIKE UPPER(?)', ["%{$q}%"]);
                  });
        }

        $products = $query->paginate(15);
        return view('inventory', compact('products', 'q'));
    }
}