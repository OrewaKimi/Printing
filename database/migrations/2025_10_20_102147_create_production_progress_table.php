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
        Schema::create('production_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('order_items', 'item_id')->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('production_stages', 'stage_id')->cascadeOnDelete();
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'on_hold', 'cancelled', 'rejected'])->default('not_started');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('paused_at')->nullable();
            $table->integer('duration')->nullable()->comment('in minutes');
            $table->foreignId('handled_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->integer('progress_percentage')->default(0);
            $table->text('notes')->nullable();
            $table->text('issues')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('order_id');
            $table->index('stage_id');
            $table->index('status');
            $table->index(['order_id', 'stage_id']);
            $table->index('handled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_progress');
    }
};
