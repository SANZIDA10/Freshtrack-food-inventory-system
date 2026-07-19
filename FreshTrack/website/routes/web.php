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
    $expiring = DB::table('batches as b')
        ->join('products as p', 'b.product_id', '=', 'p.product_id')
        ->join('categories as c', 'p.category_id', '=', 'c.category_id')
        ->select('b.batch_id', 'b.batch_code', 'p.product_name', 'c.category_name', 'b.expiry_date', 'b.quantity_available', 'b.batch_status')
        ->selectRaw('TIMESTAMPDIFF(DAY, CURDATE(), b.expiry_date) AS days_until_expiry')
        ->where('b.expiry_date', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 30 DAY)'))
        ->where('b.batch_status', 'IN_STOCK')
        ->orderBy('b.expiry_date')
        ->get();

    $expired = DB::table('batches as b')
        ->join('products as p', 'b.product_id', '=', 'p.product_id')
        ->join('categories as c', 'p.category_id', '=', 'c.category_id')
        ->select('b.batch_id', 'b.batch_code', 'p.product_name', 'c.category_name', 'b.expiry_date', 'b.quantity_available')
        ->where('b.expiry_date', '<', DB::raw('CURDATE()'))
        ->where('b.batch_status', 'IN_STOCK')
        ->orderBy('b.expiry_date')
        ->get();

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
    $recommendations = DB::table('batches as b')
        ->join('products as p', 'b.product_id', '=', 'p.product_id')
        ->select('b.batch_id', 'b.batch_code', 'p.product_name', 'b.quantity_available', 'b.expiry_date')
        ->selectRaw("CASE WHEN b.expiry_date <= DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY) THEN 'HIGH PRIORITY' WHEN b.expiry_date <= DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY) THEN 'MEDIUM PRIORITY' ELSE 'NORMAL' END AS consume_priority")
        ->orderBy('b.expiry_date', 'asc')
        ->get();

    return view('consume-first', compact('recommendations'));
});

Route::get('/waste-summary', function () {
    $summary = DB::table('waste_records as wr')
        ->join('batches as b', 'wr.batch_id', '=', 'b.batch_id')
        ->join('products as p', 'b.product_id', '=', 'p.product_id')
        ->selectRaw("'WASTED' AS status, COUNT(*) AS total_batches, SUM(wr.quantity_wasted) AS total_quantity")
        ->groupBy('status')
        ->get();

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