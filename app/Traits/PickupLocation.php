<?php


namespace App\Traits;


use App\Models\User;

trait PickupLocation
{

    public static function pickupPoint($seller_id)
    {
       $location = \Modules\Shipping\Entities\PickupLocation::where('created_by',$seller_id)->where('is_default',1)->first();
       if($location){
           return $location->id;
       }else{
           $location = \Modules\Shipping\Entities\PickupLocation::where('created_by',$seller_id)->first();
           if($location){
               return $location->id;
           }else{
               return null;
           }
       }

    }

    public static function pickupPointAddress($seller_id)
    {
       $location = \Modules\Shipping\Entities\PickupLocation::where('created_by',$seller_id)->where('is_default',1)->first();
       if($location){
           return $location;
       }else{
           $location = \Modules\Shipping\Entities\PickupLocation::where('created_by',$seller_id)->first();
           if($location){
               return $location;
           }else{
               return null;
           }
       }

    }


}
