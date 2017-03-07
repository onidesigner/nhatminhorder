<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id')->nullable()->default(0);
            $table->integer('user_id')->nullable()->default(0);
            $table->string('shop_id', 50)->nullable();
            $table->string('seller_id', 50)->nullable();
            $table->string('location_sale', 100)->nullable();
            $table->string('shop_name', 50)->nullable();
            $table->string('wangwang', 50)->nullable();
            $table->integer('step')->nullable()->default(0);
            $table->string('data_value', 100)->nullable();
            $table->string('title_origin', 100)->nullable();
            $table->string('title_translated', 100)->nullable();
            $table->string('item_id', 30)->nullable();
            $table->string('site', 10)->nullable();
            $table->integer('require_min')->nullable()->default(0);
            $table->integer('stock')->nullable()->default(0);
            $table->string('link_origin', 100)->nullable();
            $table->string('property', 50)->nullable();
            $table->string('property_translated', 50)->nullable();
            $table->string('property_md5', 50)->nullable();
            $table->string('outer_id', 10)->nullable();
            $table->integer('quantity')->nullable()->default(0);
            $table->string('image_origin', 100)->nullable();
            $table->string('image_model', 100)->nullable();
            $table->double('price_origin', 20, 2)->nullable()->default(0);
            $table->double('price_promotion', 20, 2)->nullable()->default(0);
            $table->double('price_vnd', 20, 2)->nullable()->default(0);
            $table->string('price_table', 100)->nullable();
            $table->double('weight', 20, 2)->nullable()->default(0);
            $table->string('comment', 100)->nullable();
            $table->string('comment_private', 100)->nullable();
            $table->string('comment_shop', 100)->nullable();
            $table->string('tool', 50)->nullable();
            $table->tinyInteger('error')->nullable()->default(0);
            $table->string('category_name', 50)->nullable();
            $table->integer('category_id')->nullable()->default(0);
            $table->string('brand', 50)->nullable();
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
        Schema::dropIfExists('cart_items');
    }
}
