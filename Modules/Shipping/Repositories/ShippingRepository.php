<?php

namespace Modules\Shipping\Repositories;
use Modules\Shipping\Entities\ShippingMethod;

class ShippingRepository
{
    public function getAll()
    {
        $user_id = getParentSellerId();
        $methods = ShippingMethod::where('request_by_user',$user_id)->whereHas('carrier', function($q){
            $q->where('status', 1);
        })->with(['carrier'])->get();

        if(!isModuleActive('ShipRocket')){
            $methods = $methods->filter(function($item) {
                if($item->carrier->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }

        return $methods;
    }

    public function getRequestedSellerOwnShippingMethod()
    {
        return ShippingMethod::where('request_by_user', auth()->user()->id)->where('is_approved', 0)->latest()->get();
    }

    public function getActiveAll()
    {
        $methods = ShippingMethod::where('request_by_user',auth()->user()->id)->where('is_active', 1)->whereHas('carrier', function($q){
            $q->where('status', 1);
        })->with(['carrier'])->get();
        if(!isModuleActive('ShipRocket')){
            $methods = $methods->filter(function($item) {
                if($item->carrier->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }
        $methods = $methods->filter(function($item) {
            if($item->carrier->slug == 'Shiprocket' && $item->carrier->carrierConfig->carrier_status != 1){
                return $item->id;
            }
        });
        return $methods;
    }

    public function getActiveByCarrier($id)
    {
        $methods = ShippingMethod::where('request_by_user',auth()->user()->id)->where('is_active', 1)->where('carrier_id',$id)->whereHas('carrier', function($q){
            $q->where('status', 1);
        })->with(['carrier'])->get();

        if(!isModuleActive('ShipRocket')){
            $methods = $methods->filter(function($item) {
                if($item->carrier->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }
        return $methods;
    }

    public function store(array $data)
    {
        ShippingMethod::create($data);
    }

    public function find($id)
    {
        return ShippingMethod::findOrFail($id);
    }

    public function update(array $data, $id)
    {
        $user_id = getParentSellerId();
        $method = ShippingMethod::where('id',$id)->where('request_by_user', $user_id)->first();
        if($method){
            $method->update($data);
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $user_id = getParentSellerId();
        $shipping = ShippingMethod::where('id',$id)->where('request_by_user', $user_id)->first();
        $totals = ShippingMethod::where('request_by_user', $user_id)->pluck('id')->toArray();
        if($shipping){
            if(count($shipping->methodUse) > 0){
                return 'not_possible';
            }elseif(count($totals) < 2){
                return 'not_possible_for_1';
            }else{
                $shipping->delete();
                return 'possible';
            }
        }else{
            return 'invalid';
        }

    }

    public function updateStatus(array $data)
    {
        $shipping_method = $this->find($data['id']);
        $shipping_method->is_active = $data['status'];
        $shipping_method->save();
    }

    public function updateApproveStatus($data)
    {
        $shipping_method = $this->find($data['id']);
        $shipping_method->is_approved = $data['status'];
        $shipping_method->save();
    }

    public function getActiveAllForAPI(){
        $methods = ShippingMethod::where('request_by_user',1)->where('id', '>', 1)->where('is_active', 1)->whereHas('carrier', function($q){
            $q->where('status', 1);
        })->with(['carrier'])->get();
        if(!isModuleActive('ShipRocket')){
            $methods = $methods->filter(function($item) {
                if($item->carrier->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }
        return $methods;
    }
}
