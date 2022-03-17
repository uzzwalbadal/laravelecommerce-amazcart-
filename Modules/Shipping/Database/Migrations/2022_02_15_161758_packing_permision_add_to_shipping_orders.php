<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class PackingPermisionAddToShippingOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('permissions')){

            Permission::destroy([695]);
            $sql = [
                //configuration
                ['id' => 695, 'module_id' => 44, 'parent_id' => 687, 'name' => 'packaging', 'route' => 'shipping.packaging.update', 'type' => 3 ],
            ];
            try{
                DB::table('permissions')->insert($sql);
            }catch(Exception $e){

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
        //
    }
}
