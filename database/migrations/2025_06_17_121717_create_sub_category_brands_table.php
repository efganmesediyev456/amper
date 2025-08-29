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
        Schema::create('sub_category_brends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sub_category_id")->nullable();
            $table->foreign('sub_category_id')->references("id")->on("sub_categories")->nullOnDelete();
            $table->unsignedBigInteger("brend_id")->nullable();
            $table->foreign('brend_id')->references("id")->on("brends")->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_category_brands');
    }
};
