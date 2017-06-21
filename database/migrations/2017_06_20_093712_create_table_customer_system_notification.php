<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCustomerSystemNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_system_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('object_id')->nullable()->comment('id cua doi tuong muon theo doi');
            $table->string('object_type')->nullable()->comment('kieu cua doi tuong id'); // la khieu nai hay la cua don hang
            $table->integer('follower_id')->nullable()->comment('nguoi theo doi cua don'); //
            $table->text('title')->nullable()->comment('tieu de cua thong bao');
            $table->text('notification_content')->nullable()->comment('noi dung cua thong bao');
            $table->string('type')->nullable()->comment('kieu của thong bao');
            $table->string('notify_status')->nullable()->comment('trang thái của comment');
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
        Schema::dropIfExists('customer_system_notification');
    }
}
