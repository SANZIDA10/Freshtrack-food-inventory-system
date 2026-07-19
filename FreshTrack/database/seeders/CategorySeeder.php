<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['category_name' => 'Dairy',      'description' => 'Milk and dairy products'],
            ['category_name' => 'Bakery',     'description' => 'Bread, cake, and baked items'],
            ['category_name' => 'Vegetables', 'description' => 'Fresh produce items'],
            ['category_name' => 'Frozen',     'description' => 'Frozen food and ice cream'],
            ['category_name' => 'Grains',     'description' => 'Rice, flour, and cereal products'],
            ['category_name' => 'Beverages',  'description' => 'Juice, water, and soft drinks'],
        ]);
    }
}