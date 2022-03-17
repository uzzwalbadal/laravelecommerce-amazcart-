<?php

namespace Modules\Shipping\Repositories;


use App\Models\OrderPackageDetail;

class OrderSyncRepository
{
    public function sync($package_id,$response)
    {
       return OrderPackageDetail::where('id',$package_id)->update(['carrier_order_id'=>$response['order_id'],'carrier_response'=>json_encode($response)]);
    }

    public function syncCustom($package_id,$response)
    {
        return OrderPackageDetail::where('id',$package_id)->update(['carrier_order_id'=>$response['order_id'],'carrier_response'=>json_encode($response)]);
    }



}
