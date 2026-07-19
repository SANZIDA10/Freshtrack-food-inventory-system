<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->unsignedBigInteger('category_id');
            $table->string('product_name', 120);
            $table->string('brand', 100)->nullable();
            $table->string('unit_of_measure', 30);
            $table->unsignedInteger('shelf_life_days');
            $table->unsignedInteger('reorder_level')->default(0);
            $table->string('status', 20)->default('ACTIVE');

            $table->foreign('category_id')->references('category_id')->on('categories')->cascadeOnDelete();
            $table->unique(['category_id', 'product_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};