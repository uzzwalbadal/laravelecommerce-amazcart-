<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHeightWeightInfoToOrderPackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_package_details', function (Blueprint $table) {
            $table->float('weight')->nullable()->comment('gm');
            $table->float('length')->nullable()->comment('cm');
            $table->float('breadth')->nullable()->comment('cm');
            $table->float('height')->nullable()->comment('cm');
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
            $table->dropColumn('weight');
            $table->dropColumn('length');
            $table->dropColumn('breadth');
            $table->dropColumn('height');
        });
    }
}
