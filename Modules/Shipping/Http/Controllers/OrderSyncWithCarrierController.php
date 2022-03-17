<?php

namespace Modules\Shipping\Http\Controllers;


use App\Models\OrderPackageDetail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Routing\Controller;
use Modules\Shipping\Repositories\OrderRepository;
use Exception;
use Modules\ShipRocket\Repositories\ShipRocketRepository;

class OrderSyncWithCarrierController extends Controller
{
    public function OrderSyncWithCarrier($package)
    {
        if($package->carrier){
            if($package->carrier->slug == 'Shiprocket'){
                if(sellerWiseShippingConfig($package->seller_id)['order_confirm_and_sync'] == 'Automatic'){

                    if($package->order->customer_id){
                        $deliveryPostCode = $package->order->shipping_address->postal_code;
                    }else{
                        $deliveryPostCode = $package->order->guest_info->shipping_post_code;
                    }
                    $COD = $package->order->payment_type == 1 ? 1 : 0;
                    $WEIGHT = $package->weight > 0 ? $package->weight/1000 : 0;
                    $pinCodeDetails = [
                        'pickup_postcode' => $package->pickupPoint->pin_code , //Postcode from where the order will be picked.
                        'delivery_postcode' => $deliveryPostCode, //Postcode where the order will be delivered
                        'weight' => $WEIGHT, //package weight in kgs
                        'cod' => $COD, //1 for Cash on Delivery and 0 for Prepaid orders.
                    ];

                    $shipRocketRepo = new ShipRocketRepository();
                    $courierId = $shipRocketRepo->checkRecommendedCourier($pinCodeDetails,$package);
                    if($courierId){
                        OrderPackageDetail::where('id',$package->id)->update(['shipped_by'=>$courierId]);
                    }
                }

                $shipRocket = new ShipRocketRepository();
                $shipRocket->orderCreate($package);
                return true;
            }else{
                return true;
            }

        }else {
            return true;
        }
    }


    public function orderTracking($trackingId)
    {
        try{
            $orderRepo = new OrderRepository();
            $package = $orderRepo->findOrderByTrackingId($trackingId);
            if($package){
                if($package->carrier){
                    if($package->carrier->slug == 'Shiprocket'){
                        $shipRocket = new ShipRocketRepository();
                        $res = $shipRocket->tracking($trackingId,$package);
                        if($res){
                             return $res['status'];
                        }
                        else{
                            return 'failed';
                        }
                    }else{
                        return 'something happen';
                    }

                }else {
                    return 'carrier not found';
                }
            }else{
                return 'order not found';
            }

        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }




}
