<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('customer_addresses')){
            DB::statement("ALTER TABLE `customer_addresses` CHANGE `postal_code` `postal_code` VARCHAR(255) NULL DEFAULT NULL;");
            DB::statement("ALTER TABLE `customer_addresses` CHANGE `city` `city` VARCHAR(255) NULL DEFAULT NULL;");
            DB::statement("ALTER TABLE `customer_addresses` CHANGE `phone` `phone` VARCHAR(255) NULL DEFAULT NULL;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
