<?php

namespace Modules\Shipping\Repositories;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Shipping\Entities\Carrier;
use Modules\Shipping\Entities\PickupLocation;
use Modules\Shipping\Entities\SellerWiseCarrierConfig;
use Modules\ShipRocket\Repositories\ShipRocketRepository;

class PickupLocationRepository
{
    public function all()
    {
        $user_id = getParentSellerId();
        return PickupLocation::with(['country','state','city'])->where('created_by',$user_id)->get();
    }

    public function getActiveAll()
    {
        $user_id = getParentSellerId();
        return PickupLocation::with(['country','state','city'])->where('created_by',$user_id)->where('status',1)->get();
    }

    public function create(array $data)
    {
        $user_id = getParentSellerId();
        $location =  PickupLocation::create([
            'pickup_location'=>$data['pickup_location'],
            'name'=>$data['name'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
            'address'=>$data['address'],
            'address_2'=>$data['address_2'],
            'city_id'=>isset($data['city_id'])?$data['city_id']:null,
            'state_id'=>isset($data['state_id'])?$data['state_id']:null,
            'country_id'=>isset($data['country_id'])?$data['country_id']:null,
            'pin_code'=>$data['pin_code'],
//            'lat'=>$data['lat'],
//            'long'=>$data['long'],
//            'status'=>$data['status'],
            'created_by'=>$user_id,
        ]);

        $flag = false;
        $carrier = Carrier::where('slug','Shiprocket')->first();
        if($carrier){
            $row = SellerWiseCarrierConfig::where('seller_id',$user_id)->where('carrier_id',$carrier->id)->where('carrier_status',1)->first();
            if($row){
                $flag = true;
            }
        }
        if(isModuleActive('ShipRocket') && $flag){
            $shipRocketRepo = new ShipRocketRepository();
            $shipRocketRepo->addPickupLocation($location->id);
        }

        return $location;
    }

    public function find($id)
    {
        return PickupLocation::with(['country','state','city'])->findOrFail($id);
    }

    public function update(array $data,$id)
    {
        $user_id = getParentSellerId();
        return PickupLocation::where('id',$id)->where('created_by', $user_id)->update([
            'pickup_location'=>$data['pickup_location'],
            'name'=>$data['name'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
            'address'=>$data['address'],
            'address_2'=>$data['address_2'],
            'city_id'=>isset($data['city_id'])?$data['city_id']:null,
            'state_id'=>isset($data['state_id'])?$data['state_id']:null,
            'country_id'=>isset($data['country_id'])?$data['country_id']:null,
            'pin_code'=>$data['pin_code'],
//            'lat'=>$data['lat'],
//            'long'=>$data['long'],
        ]);
    }

    public function delete($id){
        $user_id = getParentSellerId();
        $location = PickupLocation::where('id', $id)->where('created_by', $user_id)->first();
        if($location){
            $location->delete();
            return true;
        }
        return false;
    }
    public function status($data){
        return PickupLocation::findOrFail($data['id'])->update(['status' => $data['status']]);
    }

    public function setPickupLocation($id)
    {
        $user_id = getParentSellerId();
        DB::table('pickup_locations')->where('created_by',$user_id)->update(array('is_set' => 0));
        PickupLocation::where('id',$id)->update(['is_set' => 1]);
        return true;
    }

    public function setDefault($data)
    {
        $user_id = getParentSellerId();
        DB::table('pickup_locations')->where('created_by',$user_id)->update(array('is_default' => 0));
//        PickupLocation::where('created_by',Auth::id())->query()->update(['is_default' => 0]);
        return PickupLocation::findOrFail($data['id'])->update(['is_default' => $data['set_default']]);
    }

}
