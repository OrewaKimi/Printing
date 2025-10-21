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
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('report_number', 50)->unique();
            $table->date('report_date');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('report_period', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly', 'custom']);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('total_profit', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total_tax', 12, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('completed_orders')->default(0);
            $table->integer('cancelled_orders')->default(0);
            $table->integer('pending_orders')->default(0);
            $table->integer('total_customers')->default(0);
            $table->integer('new_customers')->default(0);
            $table->foreignId('generated_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('report_number');
            $table->index('report_date');
            $table->index('report_period');
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_reports');
    }
};

