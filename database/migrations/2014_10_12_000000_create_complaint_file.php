<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql =
            "
           CREATE TABLE `nhatminh247`.`complaints_files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `path` VARCHAR(45) NULL,
  `complaint_id` INT NULL,
  `file_type` VARCHAR(45) NULL,
  `create_time` DATETIME NULL,
  PRIMARY KEY (`id`));
                    
                    
            ";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
