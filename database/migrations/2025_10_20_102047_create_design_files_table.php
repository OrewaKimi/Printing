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
        Schema::create('design_files', function (Blueprint $table) {
            $table->id('file_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('order_items', 'item_id')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size', 20)->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->enum('file_category', ['customer_upload', 'designer_draft', 'final_design', 'revision', 'reference'])->default('customer_upload');
            $table->integer('version')->default(1);
            $table->foreignId('uploaded_by')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->dateTime('uploaded_date');
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->dateTime('approved_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('order_id');
            $table->index('file_category');
            $table->index('is_approved');
            $table->index('uploaded_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_files');
    }
};
