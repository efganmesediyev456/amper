<?php

// database/migrations/xxxx_xx_xx_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer("order_number")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->enum('delivery_type', ['home_delivery', 'store_pickup']);
            $table->decimal('total_amount', 10, 2);
            $table->foreign("user_id")->references("id")->on("users")->noActionOnDelete();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
