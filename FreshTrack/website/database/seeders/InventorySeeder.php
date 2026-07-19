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
            ['category_id' => 1, 'product_name' => 'Milk 1L', 'brand' => 'Prime', 'unit_of_measure' => 'Packet', 'shelf_life_days' => 7, 'reorder_level' => 10, 'status' => 'ACTIVE'],
            ['category_id' => 2, 'product_name' => 'Whole Wheat Bread', 'brand' => 'FreshBake', 'unit_of_measure' => 'Piece', 'shelf_life_days' => 5, 'reorder_level' => 15, 'status' => 'ACTIVE'],
            ['category_id' => 3, 'product_name' => 'Tomato', 'brand' => 'Local Farm', 'unit_of_measure' => 'Kg', 'shelf_life_days' => 6, 'reorder_level' => 20, 'status' => 'ACTIVE'],
        ]);
    }
}
