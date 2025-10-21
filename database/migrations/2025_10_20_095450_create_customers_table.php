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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name', 100);
            $table->string('company_name', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20);
            $table->string('email', 100);
            $table->enum('customer_type', ['personal', 'business'])->default('personal');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            // Indexes
            $table->index('email');
            $table->index('phone');
            $table->index('customer_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
