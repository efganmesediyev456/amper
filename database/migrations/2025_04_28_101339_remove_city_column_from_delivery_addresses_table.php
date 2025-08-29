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
        Schema::table('delivery_addresses', function (Blueprint $table) {
            $table->dropColumn("city");
            $table->unsignedBigInteger("city_id")->nullable();
            $table->foreign("city_id")->references("id")->on("cities")->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_addresses', function (Blueprint $table) {
            $table->string("city");
            $table->dropForeign(["city_id"]);
            $table->dropColumn("city_id");
        });
    }
};
