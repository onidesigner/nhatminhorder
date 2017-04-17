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
            CREATE TABLE `nhatminh247`.` complaints` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NULL,
  `customer_id` INT NULL,
  `description` TEXT NULL,
  `accept_by` INT NULL,
  `reject_by` INT NULL,
  `accept_time` DATETIME NULL,
  `reject_time` DATETIME NULL,
  `created_time` DATETIME NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `nhatminh247`.`complaints` 
ADD COLUMN `status` VARCHAR(45) NULL AFTER `created_time`;

ALTER TABLE `nhatminh247`.`complaints` 
ADD COLUMN `title` VARCHAR(45) NULL AFTER `status`;

ALTER TABLE `nhatminh247`.`complaints` 
ADD COLUMN `finish_time` DATETIME NULL AFTER `title`;
                    
                    
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
