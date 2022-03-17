<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Shipping\Entities\ShippingConfiguration;

class SellerDefaultShippingConfig extends Migration
{

    public function up()
    {
        $seller_role_id = \Modules\RolePermission\Entities\Role::where('type','seller')->first();
        if($seller_role_id){
            $users = \App\Models\User::where('role_id',$seller_role_id->id)->get();
            $row = ShippingConfiguration::where('seller_id',1)->first();
            foreach ($users as $user){
                $newRow = $row->replicate();
                $newRow->seller_id = $user->id;
                $newRow->order_confirm_and_sync = 'Manual';
                $newRow->save();
            }
        }
    }


}
