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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->string('transaction_number', 50)->unique();
            $table->foreignId('material_id')->constrained('materials', 'material_id')->cascadeOnDelete();
            $table->enum('transaction_type', ['in', 'out', 'adjustment', 'return', 'waste'])->default('out');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price_per_unit', 12, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->decimal('stock_before', 10, 2)->nullable();
            $table->decimal('stock_after', 10, 2)->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders', 'order_id')->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('order_items', 'item_id')->nullOnDelete();
            $table->dateTime('transaction_date');
            $table->string('reference_number', 100)->nullable();
            $table->string('supplier_invoice', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('transaction_number');
            $table->index('material_id');
            $table->index('transaction_type');
            $table->index('transaction_date');
            $table->index(['transaction_date', 'transaction_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
