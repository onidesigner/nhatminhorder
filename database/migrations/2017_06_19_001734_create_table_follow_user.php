<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFollowUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable()->commment('mã đơn hàng');
            $table->integer('notify_id')->nullable()->commment('id của notification');
            $table->integer('comment_id')->nullable()->commment('id của comment trên đơn');
            $table->integer('user_id')->nullable()->commment('id của người có liên quan trên đơn');
            $table->string('status',200)->nullable()->commment('id của người có liên quan trên đơn');
            $table->string('type',200)->nullable()->commment('loại comment trên đơn');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follow_user');
    }
}
