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
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->string("reason")->nullable();
            $table->unsignedBigInteger("reason_id")->nullable();
            $table->foreign("reason_id")->references("id")->on("order_cancellation_reasons")->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->dropColumn("reason");
            $table->dropForeign(["reason_id"]);
            $table->dropColumn("reason_id");
        });
    }
};
