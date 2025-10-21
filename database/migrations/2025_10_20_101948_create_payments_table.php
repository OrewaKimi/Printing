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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->string('payment_number', 50)->unique();
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('payment_type_id')->constrained('payment_types', 'payment_type_id')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->decimal('payment_percentage', 5, 2)->nullable();
            $table->enum('payment_method', ['cash', 'transfer', 'credit_card', 'debit_card', 'e-wallet', 'other'])->default('cash');
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->dateTime('payment_date');
            $table->string('transaction_reference', 100)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->text('payment_proof')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->dateTime('verification_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('payment_number');
            $table->index('order_id');
            $table->index('payment_status');
            $table->index('payment_date');
            $table->index(['payment_date', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};