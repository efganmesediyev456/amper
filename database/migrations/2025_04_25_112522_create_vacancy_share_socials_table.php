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
        Schema::create('vacancy_share_socials', function (Blueprint $table) {
            $table->id();
            $table->string("url")->nullable();
            $table->string("image")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->integer("order")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_share_socials');
    }
};