<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insertOrIgnore([
            ['category_id' => 1, 'category_name' => 'Dairy', 'description' => 'Milk and dairy products'],
            ['category_id' => 2, 'category_name' => 'Bakery', 'description' => 'Bread, cake, and baked items'],
            ['category_id' => 3, 'category_name' => 'Vegetables', 'description' => 'Fresh produce items'],
        ]);

        DB::table('suppliers')->insertOrIgnore([
            ['supplier_name' => 'Fresh Farm Suppliers', 'contact_person' => 'Rakib Hasan', 'phone' => '01711111111', 'email' => 'freshfarm@example.com', 'address' => 'Dhaka'],
            ['supplier_name' => 'Daily Needs Trading', 'contact_person' => 'Nusrat Jahan', 'phone' => '01722222222', 'email' => 'dailyneeds@example.com', 'address' => 'Chattogram'],
        ]);

        DB::table('users')->insertOrIgnore([
            ['full_name' => 'Admin User', 'email' => 'admin@freshtrack.com', 'phone' => '01900000001', 'role' => 'ADMIN'],
            ['full_name' => 'Inventory Manager', 'email' => 'manager@freshtrack.com', 'phone' => '01900000002', 'role' => 'MANAGER'],
        ]);

        DB::table('products')->insertOrIgnore([
            ['product_id' => 1, 'category_id' => 1, 'product_name' => 'Milk 1L', 'brand' => 'Prime', 'unit_of_measure' => 'Packet', 'shelf_life_days' => 7, 'reorder_level' => 10, 'status' => 'ACTIVE'],
            ['product_id' => 2, 'category_id' => 2, 'product_name' => 'Whole Wheat Bread', 'brand' => 'FreshBake', 'unit_of_measure' => 'Piece', 'shelf_life_days' => 5, 'reorder_level' => 15, 'status' => 'ACTIVE'],
            ['product_id' => 3, 'category_id' => 3, 'product_name' => 'Tomato', 'brand' => 'Local Farm', 'unit_of_measure' => 'Kg', 'shelf_life_days' => 6, 'reorder_level' => 20, 'status' => 'ACTIVE'],
        ]);

        DB::table('purchases')->insertOrIgnore([
            ['purchase_id' => 1, 'supplier_id' => 1, 'purchased_by' => 1, 'purchase_date' => now()->toDateString(), 'invoice_no' => 'INV-1001', 'total_amount' => 2500.00, 'status' => 'COMPLETED'],
            ['purchase_id' => 2, 'supplier_id' => 2, 'purchased_by' => 2, 'purchase_date' => now()->toDateString(), 'invoice_no' => 'INV-1002', 'total_amount' => 1800.00, 'status' => 'COMPLETED'],
        ]);

        DB::table('batches')->insertOrIgnore([
            [
                'batch_id' => 1,
                'purchase_id' => 1,
                'product_id' => 1,
                'batch_code' => 'MILK-B001',
                'manufacture_date' => now()->subDay()->toDateString(),
                'expiry_date' => now()->addDays(5)->toDateString(),
                'quantity_received' => 50,
                'quantity_available' => 50,
                'unit_cost' => 45.00,
                'storage_location' => 'Cold Storage A',
                'batch_status' => 'IN_STOCK'
            ],
            [
                'batch_id' => 2,
                'purchase_id' => 2,
                'product_id' => 2,
                'batch_code' => 'BREAD-B001',
                'manufacture_date' => now()->subDay()->toDateString(),
                'expiry_date' => now()->addDays(2)->toDateString(),
                'quantity_received' => 30,
                'quantity_available' => 30,
                'unit_cost' => 35.00,
                'storage_location' => 'Shelf 1',
                'batch_status' => 'IN_STOCK'
            ],
            [
                'batch_id' => 3,
                'purchase_id' => 2,
                'product_id' => 3,
                'batch_code' => 'VEG-B001',
                'manufacture_date' => now()->subDay()->toDateString(),
                'expiry_date' => now()->addDays(4)->toDateString(),
                'quantity_received' => 40,
                'quantity_available' => 40,
                'unit_cost' => 20.00,
                'storage_location' => 'Chiller B',
                'batch_status' => 'IN_STOCK'
            ],
        ]);

        DB::table('inventory_movements')->insertOrIgnore([
            ['movement_id' => 1, 'batch_id' => 1, 'user_id' => 2, 'movement_type' => 'IN', 'quantity' => 50, 'reference_type' => 'PURCHASE', 'reference_id' => 1, 'notes' => 'Initial stock entry'],
            ['movement_id' => 2, 'batch_id' => 2, 'user_id' => 2, 'movement_type' => 'OUT', 'quantity' => 5, 'reference_type' => 'SALE', 'reference_id' => 101, 'notes' => 'Sold to customer'],
        ]);

        DB::table('donations')->insertOrIgnore([
            ['donation_id' => 1, 'batch_id' => 2, 'approved_by' => 1, 'donation_date' => now()->toDateString(), 'organization_name' => 'Local Food Bank', 'quantity_donated' => 10, 'status' => 'APPROVED'],
        ]);

        DB::table('waste_records')->insertOrIgnore([
            ['waste_id' => 1, 'batch_id' => 3, 'recorded_by' => 2, 'waste_date' => now()->toDateString(), 'quantity_wasted' => 3, 'reason' => 'Items damaged during transport'],
        ]);

        DB::table('alerts')->insertOrIgnore([
            ['alert_id' => 1, 'batch_id' => 2, 'alert_type' => 'EXPIRY', 'status' => 'OPEN', 'message' => 'Bread batch is expiring soon'],
        ]);
    }
}
