<?php

namespace Modules\Shipping\Http\Controllers;

use App\Traits\GeneratePdf;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Exception;
use Modules\Setup\Repositories\CityRepository;
use Modules\Setup\Repositories\CountryRepository;
use Modules\Setup\Repositories\StateRepository;
use Modules\Shipping\Repositories\CarrierRepository;
use Modules\Shipping\Repositories\OrderRepository;
use Modules\Shipping\Repositories\PickupLocationRepository;
use Modules\Shipping\Repositories\ShippingRepository;
use Modules\ShipRocket\Repositories\ShipRocketRepository;
use Modules\UserActivityLog\Traits\LogActivity;

class ShippingOrderController extends Controller
{
    use GeneratePdf;

    protected $orderRepo;

    public function __construct(OrderRepository $orderRepoRepo)
    {
        $this->orderRepo = $orderRepoRepo;
    }

    public function index(Request $request)
    {
        try{

            $data['f_carrier'] = isset($request->carrier)?$request->carrier : '';
            $data['shipping_method'] = isset($request->shipping_method)?$request->shipping_method : '';
            $data['date_range_filter'] = isset($request->date_range_filter)?$request->date_range_filter : '';
            $data['package_code'] = isset($request->package_code)?$request->package_code : '';

            $filterData = [
                'carrier'           =>  $data['f_carrier'],
                'shipping_method'   =>  $data['shipping_method'],
                'date_range_filter' =>  $data['date_range_filter'],
                'package_code'      =>  $data['package_code'],
            ];
            $shippingRepo = new ShippingRepository();
            $data['shipping_methods'] = $shippingRepo->getActiveAll();

            $carrierRepo = new CarrierRepository();
            $data['carriers'] = $carrierRepo->getActiveAll();
            $data['orders'] = $this->orderRepo->pendingOrder($filterData);

            $pickupLocationRepo = new PickupLocationRepository();
            $data['pickup_locations'] = $pickupLocationRepo->getActiveAll();

            return view('shipping::order.index',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }

    }

    public function singleOrderMethodChange($id)
    {
        try{
            $data['row'] = $this->orderRepo->order($id);
            $shippingRepo = new ShippingRepository();
            $data['shipping_methods'] = $shippingRepo->getActiveByCarrier($data['row']->carrier_id);

            $data['couriers'] = [];
            $carrierRepo = new CarrierRepository();
            $data['carriers'] = $carrierRepo->getActiveAll();

            if(isModuleActive('ShipRocket') && $data['row']->carrier->slug =='Shiprocket' && $data['row']->carrier->status ==1){

                $data['couriers'] = $this->checkCourierServiceability($id);
            }

            return view('shipping::order.components._single_order_method_change',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    private function checkCourierServiceability($id)
    {
        $package = $this->orderRepo->order($id);
        if($package->order->customer_id){
            $deliveryPostCode = $package->order->shipping_address->postal_code;
        }else{
            $deliveryPostCode = $package->order->guest_info->shipping_post_code;
        }
        $COD = $package->order->payment_type == 1 ? 1 : 0;
        $WEIGHT = $package->weight > 0 ? $package->weight/1000 : 0;
        $pinCodeDetails = [
            'pickup_postcode' => pickupLocationData('pin_code') , //Postcode from where the order will be picked. //201009
            'delivery_postcode' => $deliveryPostCode, //Postcode where the order will be delivered
            'weight' => $WEIGHT, //package weight in kgs
            'cod' => $COD, //1 for Cash on Delivery and 0 for Prepaid orders.
        ];
        $shipRocketRepo = new ShipRocketRepository();
        return  $shipRocketRepo->checkCourierServiceability($pinCodeDetails,$package);
    }


    public function methodUpdate(Request $request)
    {
        $validate_rules = [
            'carrier' =>'required',
            'c_shipping_method' =>'required',
        ];
        $request->validate($validate_rules, validationMessage($validate_rules));
        try{
            $this->orderRepo->shippingMethodChange($request->except('_token'));
            return response()->json(['status' => 200]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function labelGenerate($id)
    {
        try {
            $data['order'] = $this->orderRepo->order($id);
            return $this->getPDF('shipping::order.label_pdf', $data,$data['order']->package_code.'_label');
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }

    }

    public function invoiceGenerate($id)
    {
        try {
            $data['order'] = $this->orderRepo->order($id);
            return $this->getPDF('shipping::order.invoice_pdf', $data,$data['order']->package_code.'_invoice');
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }

    }



    public function updateCarrierOrder($id)
    {
        try {
            $res = $this->orderRepo->updateCarrierOrder($id);
            return response()->json(['status' => $res]);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }

    public function editCarrierOrder($id)
    {
        try {
            $data['order'] = $this->orderRepo->order($id);
            $data['countries'] = (new CountryRepository())->getActiveAll();
            if($data['order']->order->customer_id){
                $data['states'] = (new StateRepository())->getByCountryId($data['order']->order->shipping_address->country);
                $data['cities'] = (new CityRepository())->getByStateId($data['order']->order->shipping_address->state);
            }else{
                $data['states'] = (new StateRepository())->getByCountryId($data['order']->order->guest_info->billing_state_id);
                $data['cities'] = (new CityRepository())->getByStateId($data['order']->order->guest_info->billing_city_id);
            }


            return view('shipping::order.edit',$data);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }

    public function carrierOrderUpdate(Request $request)
    {
        $request->validate([
            'customer_billing_name' => 'required',
            'customer_billing_email' => 'required',
            'customer_billing_phone' => 'required',
            'customer_billing_address' => 'required',
            'customer_billing_post_code' => 'required',
            'customer_billing_country' => 'required',
            'customer_billing_state' => 'required',
            'customer_billing_city' => 'required',
            'customer_shipping_name' => 'required',
            'customer_shipping_email' => 'required',
            'customer_shipping_phone' => 'required',
            'customer_shipping_address' => 'required',
            'customer_shipping_post_code' => 'required',
            'customer_shipping_country' => 'required',
            'customer_shipping_state' => 'required',
            'customer_shipping_city' => 'required',
            'product.*' => 'required',

        ]);
        try {
            $shipRocketRepo = new ShipRocketRepository();
            $shipRocketRepo->updateOrder($request->all());
        } catch (\Exception $e) {
            dd($e);
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }

    public function editPackaging($id)
    {
        try{
            $data['row'] = $this->orderRepo->order($id);
            return view('shipping::order.components._packaging',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function updatePackaging(Request $request)
    {
        $validate_rules = [
            'weight' =>'required',
            'length' =>'required',
            'breadth'=>'required',
            'height' =>'required',
            'id' =>'required',
        ];
        $request->validate($validate_rules, validationMessage($validate_rules));
        try {
            $res = $this->orderRepo->updatePackaging($request->except('_token'));
            return response()->json(['res' => $res]);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }

    public function carrierChange(Request $request)
    {
        try {
            $carrierRepo = new CarrierRepository();
            $carrier = $carrierRepo->find($request->carrier_id);
            $data['couriers'] = [];
            $data['shipRocket'] = false;
            if($carrier->slug == 'Shiprocket'){
                $data['couriers'] = $this->checkCourierServiceability($request->package_id);
                $data['shipRocket'] = true;
            }
            $shippingRepo = new ShippingRepository();
            $data['shipping_methods'] = $shippingRepo->getActiveByCarrier($request->carrier_id);
            $data['row'] = $this->orderRepo->order($request->package_id);
            $data['carrier'] = $carrier;
            return (string)view('shipping::order.components._shipping_change',$data);

        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }

    public function customerAddressEdit($id)
    {
        try{

            $data['order'] = $this->orderRepo->order($id);
            $data['countries'] = (new CountryRepository())->getActiveAll();
            if($data['order']->order->customer_id){
                $data['b_states'] = (new StateRepository())->getByCountryId($data['order']->order->billing_address->country);
                $data['b_cities'] = (new CityRepository())->getByStateId($data['order']->order->billing_address->state);
                $data['s_states'] = (new StateRepository())->getByCountryId($data['order']->order->shipping_address->country);
                $data['s_cities'] = (new CityRepository())->getByStateId($data['order']->order->shipping_address->state);
            }else{
                $data['b_states'] = (new StateRepository())->getByCountryId($data['order']->order->guest_info->billing_country_id);
                $data['b_cities'] = (new CityRepository())->getByStateId($data['order']->order->guest_info->billing_state_id);
                $data['s_states'] = (new StateRepository())->getByCountryId($data['order']->order->guest_info->shipping_country_id);
                $data['s_cities'] = (new CityRepository())->getByStateId($data['order']->order->guest_info->shipping_state_id);
            }
            return view('shipping::order.components._address_update',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function customerAddressUpdate(Request $request)
    {
        if($request->has('customer_address_id')){
            $request->validate([
                'customer_shipping_name' => 'required',
                'customer_shipping_email' => 'required',
                'customer_shipping_phone' => 'required',
                'customer_shipping_address' => 'required',
                'customer_shipping_post_code' => 'required',
                'customer_shipping_country' => 'required',
                'customer_shipping_state' => 'required',
                'customer_shipping_city' => 'required',
            ]);
        }else {
            $request->validate([
                'customer_billing_name' => 'required',
                'customer_billing_email' => 'required',
                'customer_billing_phone' => 'required',
                'customer_billing_address' => 'required',
                'customer_billing_post_code' => 'required',
                'customer_billing_country' => 'required',
                'customer_billing_state' => 'required',
                'customer_billing_city' => 'required',
                'customer_shipping_name' => 'required',
                'customer_shipping_email' => 'required',
                'customer_shipping_phone' => 'required',
                'customer_shipping_address' => 'required',
                'customer_shipping_post_code' => 'required',
                'customer_shipping_country' => 'required',
                'customer_shipping_state' => 'required',
                'customer_shipping_city' => 'required',
            ]);
        }


        try {
          $this->orderRepo->updateCustomerAddress($request->except('_token'));
            return response()->json(['res' => 200]);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }


    public function carrierStatus($id)
    {
        try {
            $data['row'] = $this->orderRepo->order($id);
            $orderSyncWithCarrierController = new OrderSyncWithCarrierController();
            $data['status'] = $orderSyncWithCarrierController->orderTracking($data['row']->carrier_order_id);
            return view('shipping::order.components._carrier_status',$data);

        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }
}
