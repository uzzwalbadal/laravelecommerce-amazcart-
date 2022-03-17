<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCarrierOrderIdFieldToOrderPackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_package_details', function (Blueprint $table) {
            $table->string('carrier_order_id')->nullable()->after('order_id');
            $table->string('carrier_id')->nullable()->after('shipping_method');
            $table->string('shipped_by')->nullable()->after('carrier_id');
            $table->text('carrier_response')->nullable();
            $table->unsignedBigInteger('pickup_point_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_package_details', function (Blueprint $table) {
            $table->dropColumn('carrier_order_id');
            $table->dropColumn('carrier_id');
            $table->dropColumn('shipped_by');
            $table->dropColumn('carrier_response');
            $table->dropColumn('pickup_point_id');
        });
    }
}
