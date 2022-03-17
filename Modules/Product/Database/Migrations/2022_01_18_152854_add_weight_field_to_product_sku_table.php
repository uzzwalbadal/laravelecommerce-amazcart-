<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeightFieldToProductSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_sku', function (Blueprint $table) {
            $table->string('weight')->default('500')->after('track_sku')->comment('gm');
            $table->string('length')->default('30')->after('weight')->comment('cm');
            $table->string('breadth')->default('20')->after('length')->comment('cm');
            $table->string('height')->default('10')->after('breadth')->comment('cm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_sku', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('length');
            $table->dropColumn('breadth');
            $table->dropColumn('height');
        });
    }
}
