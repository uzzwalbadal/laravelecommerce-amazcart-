<?php

use App\Models\OrderPackageDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPaidColumnToOrderPackageDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('order_package_details')){
            Schema::table('order_package_details', function (Blueprint $table) {
                $table->boolean('is_paid')->default(0)->after('is_cancelled');
            });

            $packages = OrderPackageDetail::with(['order'])->get();
            if($packages->count()){
                foreach($packages as $package){
                    $package->update([
                        'is_paid' => $package->order->is_paid
                    ]);
                }
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_package_details', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });
    }
}
