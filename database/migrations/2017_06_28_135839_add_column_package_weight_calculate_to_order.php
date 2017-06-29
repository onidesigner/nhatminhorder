<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPackageWeightCalculateToOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->double('package_weight_calculate', 20, 2)
                ->comment('can nang tinh phi')
                ->after('user_id');

            $table->double('package_weight_original', 20, 2)
                ->comment('can nang tinh')
                ->after('user_id');

            $table->double('package_weight_converted', 20, 2)
                ->comment('can nang quy doi')
                ->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            //
        });
    }
}
