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
            $table->integer('cart_id');
            $table->integer('user_id');
            $table->string('shop_id', 50);
            $table->string('seller_id', 50);
            $table->string('location_sale', 100);
            $table->string('shop_name', 50);
            $table->string('wangwang', 50);
            $table->integer('step');
            $table->string('data_value', 100);
            $table->string('title_origin', 100);
            $table->string('title_translated', 100);
            $table->string('item_id', 30);
            $table->string('site', 10);
            $table->integer('require_min');
            $table->integer('stock');
            $table->string('link_origin', 100);
            $table->string('property', 50);
            $table->string('property_translated', 50);
            $table->string('property_md5', 50);
            $table->string('outer_id', 10);
            $table->integer('quantity');
            $table->string('image_origin', 100);
            $table->string('image_model', 100);
            $table->double('price_origin', 20, 2);
            $table->double('price_promotion', 20, 2);
            $table->double('price_vnd', 20, 2);
            $table->string('price_table', 100);
            $table->double('weight', 20, 2);
            $table->string('comment', 100);
            $table->string('comment_private', 100);
            $table->string('comment_shop', 100);
            $table->string('tool', 50);
            $table->tinyInteger('error');
            $table->string('category_name', 50);
            $table->integer('category_id');
            $table->string('brand', 50);
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
