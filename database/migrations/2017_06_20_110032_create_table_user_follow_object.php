<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserFollowObject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_follow_object', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('object_id')->nullable()->comment('id cua doi tuong muon theo doi');
            $table->string('object_type')->nullable()->comment('kieu cua doi tuong id'); // la k
            $table->integer('follower_id')->nullable()->comment('nguoi theo doi cua don'); 
            $table->string('type')->nullable()->comment('kieu comment');
            $table->string('status')->nullable()->comment('trang thai cuar nguoi theo doi');
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
        Schema::dropIfExists('user_follow_object');
    }
}
