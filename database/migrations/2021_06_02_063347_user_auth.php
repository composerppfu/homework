<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_auth',function (Blueprint $table){
            $table->increments('id')->comment('編號');
            $table->string('account',64)->unique()->comment('帳號');
            $table->string('password',32)->nullable()->comment('密碼');
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
        Schema::drop('user_auth');
    }
}
