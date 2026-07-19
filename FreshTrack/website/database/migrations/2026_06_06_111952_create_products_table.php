<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->foreignId('category_id')->constrained('categories', 'category_id')->cascadeOnDelete();
            $table->string('product_name', 120);
            $table->string('brand', 100)->nullable();
            $table->string('unit_of_measure', 30);
            $table->unsignedInteger('shelf_life_days');
            $table->unsignedInteger('reorder_level')->default(0);
            $table->string('status', 20)->default('ACTIVE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};