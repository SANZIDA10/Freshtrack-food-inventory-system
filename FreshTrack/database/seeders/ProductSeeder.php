<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            ['category_id' => 1, 'product_name' => 'Milk 1L',            'brand' => 'Prime',       'unit_of_measure' => 'Packet', 'shelf_life_days' => 7,   'reorder_level' => 10, 'status' => 'ACTIVE'],
            ['category_id' => 1, 'product_name' => 'Yogurt 500g',        'brand' => 'Aarong',      'unit_of_measure' => 'Piece',  'shelf_life_days' => 14,  'reorder_level' => 5,  'status' => 'ACTIVE'],
            ['category_id' => 1, 'product_name' => 'Butter 200g',        'brand' => 'Pran',        'unit_of_measure' => 'Pack',   'shelf_life_days' => 30,  'reorder_level' => 8,  'status' => 'ACTIVE'],
            ['category_id' => 2, 'product_name' => 'Whole Wheat Bread',  'brand' => 'FreshBake',   'unit_of_measure' => 'Piece',  'shelf_life_days' => 5,   'reorder_level' => 15, 'status' => 'ACTIVE'],
            ['category_id' => 2, 'product_name' => 'Croissant',          'brand' => 'FreshBake',   'unit_of_measure' => 'Piece',  'shelf_life_days' => 3,   'reorder_level' => 10, 'status' => 'ACTIVE'],
            ['category_id' => 3, 'product_name' => 'Tomato',             'brand' => 'Local Farm',  'unit_of_measure' => 'Kg',     'shelf_life_days' => 6,   'reorder_level' => 20, 'status' => 'ACTIVE'],
            ['category_id' => 3, 'product_name' => 'Carrot',             'brand' => 'Local Farm',  'unit_of_measure' => 'Kg',     'shelf_life_days' => 10,  'reorder_level' => 15, 'status' => 'ACTIVE'],
            ['category_id' => 3, 'product_name' => 'Spinach',            'brand' => 'Green Fresh', 'unit_of_measure' => 'Kg',     'shelf_life_days' => 4,   'reorder_level' => 12, 'status' => 'ACTIVE'],
            ['category_id' => 4, 'product_name' => 'Frozen Peas 1kg',   'brand' => 'Cool Fresh',  'unit_of_measure' => 'Pack',   'shelf_life_days' => 180, 'reorder_level' => 8,  'status' => 'ACTIVE'],
            ['category_id' => 5, 'product_name' => 'Basmati Rice 5kg',  'brand' => 'Khadem',      'unit_of_measure' => 'Bag',    'shelf_life_days' => 365, 'reorder_level' => 5,  'status' => 'ACTIVE'],
            ['category_id' => 5, 'product_name' => 'All Purpose Flour',  'brand' => 'Star',        'unit_of_measure' => 'Kg',     'shelf_life_days' => 180, 'reorder_level' => 10, 'status' => 'ACTIVE'],
            ['category_id' => 6, 'product_name' => 'Orange Juice 1L',   'brand' => 'Pran',        'unit_of_measure' => 'Bottle', 'shelf_life_days' => 30,  'reorder_level' => 12, 'status' => 'ACTIVE'],
            ['category_id' => 6, 'product_name' => 'Mineral Water 500ml','brand' => 'Mum',        'unit_of_measure' => 'Bottle', 'shelf_life_days' => 730, 'reorder_level' => 20, 'status' => 'ACTIVE'],
        ]);
    }
}