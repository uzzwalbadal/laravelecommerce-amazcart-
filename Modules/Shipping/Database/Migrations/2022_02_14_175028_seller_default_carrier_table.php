<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Role;
use Modules\Shipping\Entities\Carrier;

class SellerDefaultCarrierTable extends Migration
{

    public function up()
    {
        $seller_role_id = Role::where('type','seller')->first();
        if($seller_role_id){
            $users = User::where('role_id',$seller_role_id->id)->get();
            foreach ($users as $user){
                $carrier = Carrier::where('created_by',$user->id)->get()->count();
                if($carrier < 1){
                    Carrier::create([
                        'name'=>'Manual',
                        'slug'=>'Manual',
                        'created_by'=>$user->id,
                    ]);
                }
    
            }
        }
    }


}
