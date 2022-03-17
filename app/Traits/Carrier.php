<?php


namespace App\Traits;


use Modules\Shipping\Entities\ShippingMethod;

trait Carrier
{

    public static function carrierId($method_id)
    {
        $method = ShippingMethod::with(['carrier'])->find($method_id);
        if($method){
            $carrier_id = $method->carrier->id;
            if($carrier_id){
                return $carrier_id;
            }else{
                return  null;
            }
        }else{
            return  null;
        }

    }


}
