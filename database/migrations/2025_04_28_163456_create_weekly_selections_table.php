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
        Schema::create('weekly_selections', function (Blueprint $table) {
            $table->id();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('status')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('weekly_selection_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_selection_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_selection_products');
        Schema::dropIfExists('weekly_selection_translations');
        Schema::dropIfExists('weekly_selections');
    }
};