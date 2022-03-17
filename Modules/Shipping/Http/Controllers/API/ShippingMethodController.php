<?php

namespace Modules\Shipping\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Services\ShippingService;

/**
* @group Shipping Methods
*
* APIs for shipping methods
*/
class ShippingMethodController extends Controller
{

    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Shipping List
     * @response{
     *      "shippings": [
     *           {
     *               "id": 1,
     *               "method_name": "Email Delivery (within 24 Hours)",
     *               "logo": null,
     *               "phone": "25656895655",
     *               "shipment_time": "12-24 hrs",
     *               "cost": 0,
     *               "is_active": 1,
     *               "request_by_user": null,
     *               "is_approved": 1,
     *               "created_at": null,
     *               "updated_at": "2021-08-08T04:05:13.000000Z"
     *           },
     *           {
     *               "id": 2,
     *               "method_name": "Flat Rate",
     *               "logo": null,
     *               "phone": "5466523263565",
     *               "shipment_time": "3-5 days",
     *               "cost": 20,
     *               "is_active": 1,
     *               "request_by_user": null,
     *               "is_approved": 1,
     *               "created_at": null,
     *               "updated_at": "2021-08-08T04:05:46.000000Z"
     *           },
     *           {
     *               "id": 3,
     *               "method_name": "Free Shipping",
     *               "logo": null,
     *               "phone": "56563565656",
     *               "shipment_time": "8-12 days",
     *               "cost": 0,
     *               "is_active": 1,
     *               "request_by_user": null,
     *               "is_approved": 1,
     *               "created_at": "2021-08-08T04:18:37.000000Z",
     *               "updated_at": "2021-08-08T04:18:37.000000Z"
     *           }
     *       ],
     *       "msg": "success"
     * 
     * }
     */

    public function index(){
        $shippings = $this->shippingService->getActiveAllForAPI();
        if(count($shippings) > 0){
            return response()->json([
                'shippings' => $shippings,
                'msg' => 'success'
            ]);
        }else{
            return response()->json([
                'msg' => 'empty list'
            ]);
        }
    }
}
