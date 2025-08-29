<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->text('address');
            $table->unsignedBigInteger('order_id');
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->noActionOnDelete();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
