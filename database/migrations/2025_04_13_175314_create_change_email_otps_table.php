<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeEmailOtpsTable extends Migration
{
    public function up()
    {
        Schema::create('change_email_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('no action');
            $table->string('otp', 4);
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('change_email_otps');
    }
}

