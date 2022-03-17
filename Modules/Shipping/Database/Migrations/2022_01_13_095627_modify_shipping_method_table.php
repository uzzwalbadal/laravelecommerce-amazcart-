<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyShippingMethodTable extends Migration
{

    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('carrier_id')->default(1)->after('method_name');
            $table->string('cost_based_on')->default('Flat')->after('carrier_id');
            $table->foreign('carrier_id')->on('carriers')->references('id')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('carrier_id');
            $table->dropColumn('cost_based_on');
        });
    }
}
