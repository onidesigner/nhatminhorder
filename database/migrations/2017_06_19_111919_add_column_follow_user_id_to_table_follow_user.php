<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFollowUserIdToTableFollowUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('follow_user', function (Blueprint $table) {
            $table->integer('follow_user_id')->nullable()->comment('id cua nguoi theo doi tren don')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follow_user', function (Blueprint $table) {
            //
        });
    }
}
