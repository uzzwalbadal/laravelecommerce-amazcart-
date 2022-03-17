<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyShippingConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('shipping_configurations')->delete();

        if(!Schema::hasColumn('shipping_configurations','seller_id')){
            Schema::table('shipping_configurations', function (Blueprint $table) {
                $table->unsignedBigInteger('seller_id')->nullable();
                $table->string('order_confirm_and_sync')->nullable();
                $table->boolean('carrier_show_for_customer')->nullable();
                $table->unsignedBigInteger('default_carrier')->nullable();
                $table->boolean('order_auto_confirm')->nullable();
                $table->unsignedBigInteger('pickup_location')->nullable();
                $table->boolean('refund_order_sync_carrier')->nullable();
                $table->boolean('seller_use_shiproket')->nullable();
                $table->string('carrier_order_type')->nullable();
                $table->string('label_code')->nullable();
            });
        }
        if (Schema::hasColumn('shipping_configurations', 'key')) {
            Schema::table('shipping_configurations', function (Blueprint $table) {
                $table->dropColumn('key');
            });
        }
        if (Schema::hasColumn('shipping_configurations', 'value')) {
            Schema::table('shipping_configurations', function (Blueprint $table) {
                $table->dropColumn('value');
            });
        }

        $datas = [];
        foreach (\Modules\Shipping\Entities\ShippingConfiguration::get() as  $setting) {
            $datas[$setting->key] = $setting->value;
        }

        DB::table('shipping_configurations')->insert([
            // [
            //    'seller_id'=>1,
            //    'order_confirm_and_sync'=>$datas['order_confirm_and_sync']?$datas['order_confirm_and_sync']:"Manual",
            //    'carrier_show_for_customer'=>$datas['carrier_show_for_customer']?$datas['carrier_show_for_customer']:0,
            //    'default_carrier'=>$datas['carrier_show_for_customer']?$datas['carrier_show_for_customer']:null,
            //    'order_auto_confirm'=>$datas['order_auto_confirm']?$datas['order_auto_confirm']:0,
            //    'pickup_location'=>$datas['pickup_location']?$datas['pickup_location']:1,
            //    'refund_order_sync_carrier'=>$datas['refund_order_sync_carrier']?$datas['refund_order_sync_carrier']:0,
            //    'seller_use_shiproket'=>$datas['seller_use_shiproket']?$datas['seller_use_shiproket']:0,
            //    'carrier_order_type'=>$datas['carrier_order_type']?$datas['carrier_order_type']:'Custom',
            //    'label_code'=>$datas['label_code']?$datas['label_code']:'barcode',
            // ]
            [
               'seller_id'=>1,
               'order_confirm_and_sync'=>"Manual",
               'carrier_show_for_customer'=>0,
               'default_carrier'=>null,
               'order_auto_confirm'=>0,
               'pickup_location'=>1,
               'refund_order_sync_carrier'=>0,
               'seller_use_shiproket'=>0,
               'carrier_order_type'=>'Custom',
               'label_code'=>'barcode',
            ]
        ]);


    }

    public function down()
    {
        Schema::table('shipping_configurations', function (Blueprint $table) {
            $table->dropColumn('seller_id');
            $table->dropColumn('order_confirm_and_sync');
            $table->dropColumn('carrier_show_for_customer');
            $table->dropColumn('default_carrier');
            $table->dropColumn('order_auto_confirm');
            $table->dropColumn('pickup_location');
            $table->dropColumn('refund_order_sync_carrier');
            $table->dropColumn('seller_use_shiproket');
            $table->dropColumn('carrier_order_type');
            $table->dropColumn('label_code');
        });
    }
}
