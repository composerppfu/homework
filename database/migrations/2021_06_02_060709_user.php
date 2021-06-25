<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_table',function (Blueprint $table){
            $table->integer('user_id')->primary()->comment('編號');
            $table->string('name',64)->nullable()->comment('姓名');
            $table->string('phone',32)->nullable()->comment('手機號碼');
            $table->string('address',255)->nullable()->comment('地址');
            $table->enum('user_role',['normal','guest','admin'])->comment('使用者身份 (訪客: guest, 一般: normal, 管理者: admin)');
            $table->dateTime('create_time')->comment('建立時間');
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
        Schema::drop('user_table');
    }
}
