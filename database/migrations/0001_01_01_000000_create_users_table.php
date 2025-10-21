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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 50)->nullable()->unique();
            $table->string('password');
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('full_name', 100)->nullable(); // PASTIKAN ADA INI
            $table->enum('role', ['customer', 'customer_service', 'production', 'designer', 'admin'])->default('customer');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('username');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};