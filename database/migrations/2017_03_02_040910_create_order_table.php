<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
//            $table->char('code', 100);
//            $table->string('avatar', 255);
//            $table->string('status', 50);
//            $table->string('seller_name', 200);
//            $table->string('seller_id', 50);
//            $table->string('seller_aliwang', 100);
//            $table->string('seller_homeland', 100);
//            $table->text('seller_info');
//            $table->integer('buyer_id');
//            $table->tinyInteger('product_group_id');
//            $table->integer('order_quantity');
//            $table->integer('pending_quantity');
//            $table->integer('recive_quantity');
//            $table->integer('packages_quantity');
//            $table->enum('customer_confirm', ['CONFIRMED','WAIT','NONE'])->default('NONE');
//            $table->string('note_customer_confirm', 255);
//            $table->double('temporary_total_amount', 20, 2);
//            $table->double('total_amount', 20, 2);
//            $table->string('receive_warehouse', 50);
//            $table->double('order_amount', 20, 2);
//            $table->double('real_amount', 20, 2);
//            $table->double('bought_amount', 20, 2);
//            $table->double('deposit_amount', 20, 2);
//            $table->decimal('deposit_ratio', 3, 2);
//            $table->decimal('deposit_ratio_origin', 3, 2);
//            $table->double('refund_amount', 20, 2);
//            $table->double('real_payment_amount', 20, 2);
//            $table->double('real_refund_amount', 20, 2);
//            $table->double('refund_by_order', 20, 2);
//            $table->double('refund_by_complaint', 20, 2);
//            $table->double('debit_amount', 20, 2);
//            $table->double('real_surcharge', 20, 2);
//            $table->double('real_service_amount', 20, 2);
//            $table->double('alipay_cny_refund_total', 20, 2);
//            $table->double('service_fee', 20, 2);
//            $table->double('domestic_shipping_fee', 20, 2);


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
        Schema::dropIfExists('order');
    }
}
