<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('supplier_name', 120);
            $table->string('contact_person', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 120)->nullable()->unique();
            $table->string('address', 255)->nullable();
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('purchase_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('purchased_by');
            $table->date('purchase_date')->default(DB::raw('CURRENT_DATE'));
            $table->string('invoice_no', 50)->nullable()->unique();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('status', 20)->default('COMPLETED');

            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->cascadeOnDelete();
            $table->foreign('purchased_by')->references('user_id')->on('users')->cascadeOnDelete();
        });

        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('batch_id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->string('batch_code', 50)->unique();
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date');
            $table->integer('quantity_received');
            $table->integer('quantity_available');
            $table->decimal('unit_cost', 12, 2);
            $table->string('storage_location', 100)->nullable();
            $table->string('batch_status', 20)->default('IN_STOCK');

            $table->foreign('purchase_id')->references('purchase_id')->on('purchases')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete();
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->bigIncrements('movement_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('user_id');
            $table->string('movement_type', 20);
            $table->timestamp('movement_date')->useCurrent();
            $table->integer('quantity');
            $table->string('reference_type', 30)->nullable();
            $table->integer('reference_id')->nullable();
            $table->string('notes', 255)->nullable();

            $table->foreign('batch_id')->references('batch_id')->on('batches')->cascadeOnDelete();
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('donation_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('approved_by');
            $table->date('donation_date')->default(DB::raw('CURRENT_DATE'));
            $table->string('organization_name', 150);
            $table->integer('quantity_donated');
            $table->string('status', 20)->default('APPROVED');

            $table->foreign('batch_id')->references('batch_id')->on('batches')->cascadeOnDelete();
            $table->foreign('approved_by')->references('user_id')->on('users')->cascadeOnDelete();
        });

        Schema::create('waste_records', function (Blueprint $table) {
            $table->bigIncrements('waste_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('recorded_by');
            $table->date('waste_date')->default(DB::raw('CURRENT_DATE'));
            $table->integer('quantity_wasted');
            $table->string('reason', 255);

            $table->foreign('batch_id')->references('batch_id')->on('batches')->cascadeOnDelete();
            $table->foreign('recorded_by')->references('user_id')->on('users')->cascadeOnDelete();
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->bigIncrements('alert_id');
            $table->unsignedBigInteger('batch_id');
            $table->string('alert_type', 30);
            $table->timestamp('alert_date')->useCurrent();
            $table->string('status', 20)->default('OPEN');
            $table->string('message', 255);

            $table->foreign('batch_id')->references('batch_id')->on('batches')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('waste_records');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('batches');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('suppliers');
    }
};
