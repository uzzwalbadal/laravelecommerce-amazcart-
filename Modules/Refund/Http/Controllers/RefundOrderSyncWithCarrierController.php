<?php

namespace Modules\Refund\Http\Controllers;


use Brian2694\Toastr\Toastr;
use Illuminate\Routing\Controller;
use Modules\Refund\Entities\RefundRequest;
use Modules\Refund\Entities\RefundRequestDetail;
use Modules\ShipRocket\Repositories\ShipRocketRepository;


class RefundOrderSyncWithCarrierController extends Controller
{
    public function refundOrderSyncWithCarrier($id)
    {

        try {
            $refund_request = RefundRequest::findOrFail($id);
            if(isModuleActive('ShipRocket') && $refund_request->shipping_gateway->carrier->slug == 'Shiprocket' && $refund_request->shipping_gateway->carrier->status ==1){
                $shipRocketRepo = new ShipRocketRepository();
                $res = $shipRocketRepo->refundOrderCreate($id);
                return true;
            }

        } catch (\Exception $e) {

            Toastr::error(__('common.operation_failed'));
            return true;
        }

    }
}
