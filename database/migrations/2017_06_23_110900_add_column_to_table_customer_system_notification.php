<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTableCustomerSystemNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_system_notification', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->comment('id cua nguoi chat , nguoi tao comment')->after('follower_id');
            $table->integer('is_deleted')->default(0)->comment('kiem tra xoa hay ko')->after('notify_status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_system_notification', function (Blueprint $table) {
            //
        });
    }
}
