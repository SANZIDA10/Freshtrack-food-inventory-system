<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $categories = Category::withCount('products')
        ->orderBy('category_name')
        ->get();

    $products = Product::with('category')
        ->orderByDesc('product_id')
        ->take(6)
        ->get();

    return view('home', compact('categories', 'products'));
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

Route::get('/expiry-alerts', function () {
    $expiring = DB::select("
        SELECT b.batch_id, b.batch_code, p.product_name, c.category_name,
               b.expiry_date, b.quantity_available, b.batch_status,
               ROUND(b.expiry_date - SYSDATE) AS days_until_expiry
        FROM batches b
        JOIN products p ON b.product_id = p.product_id
        JOIN categories c ON p.category_id = c.category_id
        WHERE b.expiry_date <= SYSDATE + 30
        AND b.batch_status = 'IN_STOCK'
        ORDER BY b.expiry_date ASC
    ");

    $expired = DB::select("
        SELECT b.batch_id, b.batch_code, p.product_name, c.category_name,
               b.expiry_date, b.quantity_available
        FROM batches b
        JOIN products p ON b.product_id = p.product_id
        JOIN categories c ON p.category_id = c.category_id
        WHERE b.expiry_date < SYSDATE
        AND b.batch_status = 'IN_STOCK'
        ORDER BY b.expiry_date ASC
    ");

    return view('expiry-alerts', compact('expiring', 'expired'));
});

Route::get('/products/{id}/batches', function ($id) {
    $product = DB::table('products')->where('product_id', $id)->first();

    if (!$product) {
        abort(404, 'Product not found');
    }

    $batches = DB::table('batches')
        ->where('product_id', $id)
        ->orderBy('expiry_date', 'asc')
        ->get();

    return view('batch-details', compact('product', 'batches'));
})->name('products.batches');

Route::get('/suppliers', function () {
    $suppliers = DB::table('suppliers')
        ->orderBy('supplier_name')
        ->get();

    return view('suppliers', compact('suppliers'));
});

Route::get('/consume-first', function () {
    
    $recommendations = DB::select("SELECT * FROM v_consume_first");

    return view('consume-first', compact('recommendations'));
});

Route::get('/waste-summary', function () {
    $summary = DB::select("SELECT * FROM v_waste_summary");
    return view('waste-summary', compact('summary'));
});

Route::get('/purchase-history', function () {
    $purchases = DB::table('purchases')
        ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.supplier_id')
        ->select(
            'purchases.purchase_id',
            'purchases.purchase_date',
            'purchases.total_amount',
            'suppliers.supplier_name'
        )
        ->orderByDesc('purchases.purchase_date')
        ->get();

    return view('purchase-history', compact('purchases'));
});