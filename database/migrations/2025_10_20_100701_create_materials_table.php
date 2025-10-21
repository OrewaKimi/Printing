<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id('material_id');
            $table->string('material_name', 100)->unique();
            $table->string('material_code', 50)->unique()->nullable();
            $table->decimal('price_per_unit', 12, 2);
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->enum('unit', ['m2', 'lembar', 'roll', 'kg', 'meter', 'pcs'])->default('m2');
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->string('supplier_name', 100)->nullable();
            $table->string('supplier_contact', 100)->nullable();
            $table->text('supplier_address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('material_name');
            $table->index('material_code');
            $table->index(['stock_quantity', 'minimum_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
