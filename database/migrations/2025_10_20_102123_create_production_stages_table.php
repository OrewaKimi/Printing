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
        Schema::create('production_stages', function (Blueprint $table) {
            $table->id('stage_id');
            $table->string('stage_name', 100)->unique();
            $table->string('stage_code', 20)->unique();
            $table->text('description')->nullable();
            $table->integer('sequence_order')->default(0);
            $table->integer('estimated_duration')->default(0)->comment('in minutes');
            $table->string('color', 20)->default('#000000');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('stage_code');
            $table->index('sequence_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_stages');
    }
};
