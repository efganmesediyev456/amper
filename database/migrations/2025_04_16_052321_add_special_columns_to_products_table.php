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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_seasonal')->default(false)->after('quantity');
            $table->boolean('is_special_offer')->default(false)->after('is_seasonal');
            $table->boolean('is_bundle')->default(false)->after('is_special_offer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_seasonal', 'is_special_offer', 'is_bundle']);
        });
    }
};
