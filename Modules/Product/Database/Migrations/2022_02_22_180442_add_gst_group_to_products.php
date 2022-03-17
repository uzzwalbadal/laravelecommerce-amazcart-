<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGstGroupToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('gst_group_id')->nullable()->after('tax_type');
            });
        }

        if(Schema::hasTable('seller_products')){
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('gst_group_id');
            });
        }
    }
}
