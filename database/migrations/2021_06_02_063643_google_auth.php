<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GoogleAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('google_auth',function (Blueprint $table){
            $table->integer('user_id')->comment('編號');
            $table->string('google_user_id',64)->unique()->comment('google使用者ID');
            $table->string('google_email',255)->unique()->comment('google使用者email');
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
        Schema::drop('google_auth');
    }
}
