<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sendotp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('otp_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone',32);
            $table->string('code',6);
            $table->enum('action', ['login', 'register', 'change']);
            $table->enum('is_use', ['0', '1']);
            $table->dateTime('expire_time');
            $table->string('ip',32);
            $table->dateTime('create_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('otp_code');
    }
}
