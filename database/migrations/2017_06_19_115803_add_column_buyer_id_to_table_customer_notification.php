<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBuyerIdToTableCustomerNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_notification', function (Blueprint $table) {
            $table->integer('buyer_id')->nullable()->comment('id cua nguoi mua hang')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_notification', function (Blueprint $table) {
            //
        });
    }
}
