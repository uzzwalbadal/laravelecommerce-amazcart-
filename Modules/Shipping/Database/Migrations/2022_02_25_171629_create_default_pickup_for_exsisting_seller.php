<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Role;
use Modules\Shipping\Entities\PickupLocation;

class CreateDefaultPickupForExsistingSeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seller_role_id = Role::where('type','seller')->first();
        if($seller_role_id){
            $users = User::where('role_id',$seller_role_id->id)->get();
            $location = PickupLocation::where('created_by',1)->first();
            foreach ($users as $user){
                $pickupLocation = PickupLocation::where('created_by', $user->id)->get()->count();
                
                if($pickupLocation < 1){
                    $newLocation = $location->replicate();
                    $newLocation->created_by = $user->id;
                    $newLocation->is_set = 1;
                    $newLocation->is_default = 1;
                    $newLocation->status = 1;
                    $newLocation->save();
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
        Schema::dropIfExists('');
    }
}
