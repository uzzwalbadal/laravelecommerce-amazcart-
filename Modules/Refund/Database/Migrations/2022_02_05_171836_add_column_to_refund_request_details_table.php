<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToRefundRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_request_details', function (Blueprint $table) {
            $table->string('carrier_order_id')->nullable()->after('processing_state');
            $table->text('carrier_response')->nullable()->after('carrier_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_request_details', function (Blueprint $table) {
            $table->dropColumn('carrier_order_id');
            $table->dropColumn('carrier_response');
        });
    }
}
