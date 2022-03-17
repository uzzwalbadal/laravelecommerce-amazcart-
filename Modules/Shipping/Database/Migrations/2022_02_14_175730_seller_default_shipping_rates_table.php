<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Role;
use Modules\Shipping\Entities\Carrier;
use Modules\Shipping\Entities\ShippingMethod;

class SellerDefaultShippingRatesTable extends Migration
{

    public function up()
    {
        $seller_role_id = Role::where('type','seller')->first();
        if($seller_role_id){
            $users = User::where('role_id',$seller_role_id->id)->get();
            foreach ($users as $user){
                $carrier = Carrier::where('created_by',$user->id)->first();
                $shipping_rates = ShippingMethod::where('request_by_user',$user->id)->get()->count();
                $shipping = [
                    'method_name' => 'Flat Rate',
                    'shipment_time' => '0-3 days',
                    'cost' => 20,
                    'is_active' => 1,
                    'request_by_user' => $user->id,
                    'is_approved' => 1
                ];
                if($shipping_rates < 1){
                    ShippingMethod::create($shipping);
                }
                DB::table('shipping_methods')->where('request_by_user', $user->id)->update(array('carrier_id' => $carrier->id));

            }
        }
    }


}
