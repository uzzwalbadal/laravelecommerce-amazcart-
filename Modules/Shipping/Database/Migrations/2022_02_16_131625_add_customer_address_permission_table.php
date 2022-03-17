<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerAddressPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = [
            //configuration
            ['id' => 697, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Address Update', 'route' => 'shipping.customer_address_update', 'type' => 3 ],
            ['id' => 698, 'module_id' => 44, 'parent_id' => 363, 'name' => 'Carrier Status', 'route' => 'shipping.carrier_status', 'type' => 3 ],
        ];
        try{
            DB::table('permissions')->insert($sql);
        }catch(Exception $e){

        }
    }


}
