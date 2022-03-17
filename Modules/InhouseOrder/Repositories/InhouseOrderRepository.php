<?php
namespace Modules\InhouseOrder\Repositories;

use App\Models\GuestOrderDetail;
use App\Models\Order;
use App\Models\OrderPackageDetail;
use App\Models\OrderProductDetail;
use App\Models\User;
use App\Repositories\CheckoutRepository;
use App\Traits\PickupLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\GST\Entities\OrderPackageGST;
use Modules\InhouseOrder\Entities\InhouseOrderCart;
use Modules\PaymentGateway\Entities\PaymentMethod;
use Modules\Seller\Entities\SellerProduct;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\Setup\Entities\Country;
use Modules\Shipping\Entities\ShippingMethod;

class InhouseOrderRepository
{
    use PickupLocation;
    public function getProducts(){

        return SellerProduct::where('status', 1)->whereHas('product', function($q){
            return $q->where('is_physical', 1);
        })->latest()->activeSeller()->get();
    }

    public function getCountries(){
        return Country::where('status', 1)->orderBy('name')->get();
    }

    public function getVariantByProduct($id){
        
        return SellerProduct::where('status', 1)->where('id', $id)->first();
    }

    public function productTypeCheck($id){
        $product = SellerProduct::findOrFail($id);
        if($product->product->product_type == 1){
            return 'single_product';
        }else{
            return 'variant_product';
        }
        return false;
    }

    public function addToCart($id){
        $product = SellerProduct::findOrFail($id);

        $selling_price = 0;
        $productSKU = $product->skus->first();
        $actual_selling_price = $productSKU->selling_price;

        if($product->hasDeal){
            $selling_price = selling_price($actual_selling_price,$product->hasDeal->discount_type, $product->hasDeal->discount);
        }
        else{

            if($product->hasDiscount == 'yes'){
                $selling_price = selling_price($actual_selling_price, $product->discount_type, $product->discount);
            }else{
                $selling_price = $actual_selling_price;
            }
        }

        $exsitCheck = InhouseOrderCart::where('product_id', $product->skus->first()->id)->where('user_id', auth()->id())->first();
        $is_out_of_stock = 0;

        if($exsitCheck){
            if($productSKU->product_stock <= $productSKU->product->product->minimum_order_qty && $productSKU->product->stock_manage == 1){
                $is_out_of_stock = 1;
            }
        }

        if($is_out_of_stock == 0){
            if($exsitCheck){
                $exsitCheck->update([
                    'qty' => $exsitCheck->qty + 1,
                    'price' => $exsitCheck->price + $selling_price,
                    'total_price' => $exsitCheck->total_price + $selling_price,
                    'user_id' => auth()->id()
                ]);
            }else{
                InhouseOrderCart::create([
                    'seller_id' => $product->user_id,
                    'product_id' => $product->skus->first()->id,
                    'qty' => 1,
                    'price' => $selling_price,
                    'total_price' => $selling_price,
                    'sku' => null,
                    'is_select' => 1,
                    'shipping_method_id' => 0,
                    'user_id' => auth()->id()
                ]);
    
            }
            $package_wise_shipping = session()->get('inhouseOrderShippingCost');
            $cartData = $this->getInhouseCartData();
            $this->generateShippingCost($cartData);

        }else{
            return 'out_of_stock';
        }

        return 'done';

    }

    public function generateShippingCost($cartData){
        $package_wise_shipping = session()->get('inhouseOrderShippingCost');
        $session_packages = [];
        $checkoutRepo = new CheckoutRepository();
        $shippingMethods = $checkoutRepo->get_active_shipping_methods();
        foreach($cartData as $seller_id => $items){

            $additional_cost = 0;
            $totalItemPrice = 0;
            $totalItemWeight = 0;
            $totalItemBreadth = 0;
            $totalItemLength = 0;
            $totalItemHeight = 0;
            $package_cost = 0;
            foreach($items as $item){
                $additional_cost += $item->product->sku->additional_shipping;
                $totalItemPrice += $item->total_price;
                $totalItemWeight += !empty($item->product->sku->weight) ? $item->qty * $item->product->sku->weight : 0;
                $totalItemHeight += $item->qty * $item->product->sku->height;
                $totalItemLength += $item->qty * $item->product->sku->length;
                $totalItemBreadth += $item->qty * $item->product->sku->breadth;
            }
            if($package_wise_shipping && array_key_exists($seller_id, $package_wise_shipping) && $package_wise_shipping[$seller_id]['shipping_id']){
                $shipping_method = ShippingMethod::with(['carrier'])->findOrFail($package_wise_shipping[$seller_id]['shipping_id']);
            }else{
                    $a_carriers = \Modules\Shipping\Entities\Carrier::where('type','Automatic')->whereHas('carrierConfigFrontend',function ($q) use ($seller_id){
                        $q->where('seller_id',$seller_id)->where('carrier_status',1);
                    });
                    $m_carriers = \Modules\Shipping\Entities\Carrier::where('type','Manual')->where('status', 1)->where('created_by',$seller_id);
                    if(sellerWiseShippingConfig(1)['seller_use_shiproket']){
                        $carriers = $a_carriers->unionAll($m_carriers)->get()->pluck('id')->toArray();
                    }else{
                        $carriers = $m_carriers->get()->pluck('id')->toArray();
                    }
                $shipping_method = $shippingMethods->where('request_by_user',$seller_id)->whereIn('carrier_id',$carriers)->first();
            }
            if($shipping_method->cost_based_on == 'Price'){
                if($totalItemPrice > 0 && $shipping_method->cost > 0){
                    $package_cost = ($totalItemPrice / 100) *  $shipping_method->cost + $additional_cost;
                }

            }elseif ($shipping_method->cost_based_on == 'Weight'){
                if($totalItemWeight > 0 && $shipping_method->cost > 0){
                    $package_cost = ($totalItemWeight / 100) *  $shipping_method->cost + $additional_cost;
                }
            }else{
                if($shipping_method->cost > 0){
                    $package_cost = $shipping_method->cost + $additional_cost;
                }
            }
            $session_packages[$seller_id] = [
                'seller_id'=>$seller_id,
                'shipping_cost'=>$package_cost,
                'additional_cost'=>$additional_cost,
                'totalItemPrice'=>$totalItemPrice,
                'totalItemWeight'=>$totalItemWeight,
                'totalItemHeight'=>$totalItemHeight,
                'totalItemLength'=>$totalItemLength,
                'totalItemBreadth'=>$totalItemBreadth,
                'shipping_id'=>$shipping_method->id,
                'shipping_method'=>$shipping_method->method_name,
                'shipping_time'=>$shipping_method->shipment_time
            ];

        }
        session()->forget('inhouseOrderShippingCost');
        session(['inhouseOrderShippingCost'=>$session_packages]);
    }

    public function storeVariantProductToCart($data){

        $productSKU = SellerProductSKU::findOrFail($data['product_id']);
        $exsitCheck = InhouseOrderCart::where('product_id', $data['product_id'])->first();
        $is_out_of_stock = 0;
        
        if($exsitCheck){
            if($productSKU->product_stock <= $productSKU->product->product->minimum_order_qty && $productSKU->product->stock_manage == 1){
                $is_out_of_stock = 1;
            }
        }

        if($is_out_of_stock == 0){
            if($exsitCheck){
                $exsitCheck->update([
                    'qty' => $exsitCheck->qty + $data['qty'],
                    'price' => $data['price'],
                    'total_price' => $exsitCheck->total_price + $data['price']
                ]);
            }else{
                InhouseOrderCart::create([
                    'seller_id' => $productSKU->product->user_id,
                    'product_id' => $data['product_id'],
                    'qty' => $data['qty'],
                    'price' => $data['price'],
                    'total_price' => $data['price'] * $data['qty'],
                    'sku' => null,
                    'is_select' => 1,
                    'shipping_method_id' => 0
                ]);
            }
        }else{
            return 'out_of_stock';
        }

        return 'done';

    }

    public function getInhouseCartData(){
        $query = InhouseOrderCart::where('is_select', 1)->where('user_id', auth()->id())->whereHas('product', function($query){
            return $query->where('status', 1)->whereHas('product', function($q){
                return $q->activeSeller();
            });
        })->get();

        $recs = new \Illuminate\Database\Eloquent\Collection($query);

        $grouped = $recs->groupBy('seller_id');

        return $grouped;

    }

    public function changeShippingMethod($data){
        $package_wise_shippings = session()->get('inhouseOrderShippingCost');
        $new_package = [];
        foreach($package_wise_shippings as $seller_id => $shipping){
            if($shipping['seller_id'] == $data['seller_id']){
                $shipping_method = ShippingMethod::with(['carrier'])->findOrFail($data['method_id']);
                $package_cost = 0;

                if($shipping_method->cost_based_on == 'Price'){
                    if($shipping['totalItemPrice'] > 0 && $shipping_method->cost > 0){
                        $package_cost = ($shipping['totalItemPrice'] / 100) *  $shipping_method->cost + $shipping['additional_cost'];
                    }

                }elseif ($shipping_method->cost_based_on == 'Weight'){
                    if($shipping['totalItemWeight'] > 0 && $shipping_method->cost > 0){
                        $package_cost = ($shipping['totalItemWeight'] / 100) *  $shipping_method->cost + $shipping['additional_cost'];
                    }
                }else{
                    if($shipping_method->cost > 0){
                        $package_cost = $shipping_method->cost + $shipping['additional_cost'];
                    }
                }
                $new_package[$seller_id] = [
                    'seller_id'=>$seller_id,
                    'shipping_cost'=>$package_cost,
                    'totalItemPrice'=> $shipping['totalItemPrice'],
                    'additional_cost'=>$shipping['additional_cost'],
                    'totalItemWeight'=>$shipping['totalItemWeight'],
                    'totalItemHeight'=>$shipping['totalItemHeight'],
                    'totalItemLength'=>$shipping['totalItemLength'],
                    'totalItemBreadth'=>$shipping['totalItemBreadth'],
                    'shipping_id'=>$data['method_id'],
                    'shipping_method'=>$shipping_method->method_name,
                    'shipping_time'=>$shipping_method->shipment_time
                ];
            }else{
                $new_package[$seller_id] = [
                    'seller_id'=>$shipping['seller_id'],
                    'shipping_cost'=>$shipping['shipping_cost'],
                    'additional_cost'=>$shipping['additional_cost'],
                    'totalItemPrice'=>$shipping['totalItemPrice'],
                    'totalItemWeight'=>$shipping['totalItemWeight'],
                    'totalItemHeight'=>$shipping['totalItemHeight'],
                    'totalItemLength'=>$shipping['totalItemLength'],
                    'totalItemBreadth'=>$shipping['totalItemBreadth'],
                    'shipping_id'=>$shipping['shipping_id'],
                    'shipping_method'=>$shipping['shipping_method'],
                    'shipping_time'=>$shipping['shipping_time']
                ];
            }
        }
        session()->forget('inhouseOrderShippingCost');
        session(['inhouseOrderShippingCost'=>$new_package]);
        return true;
    }

    public function changeQty($data){

        $cartProduct = InhouseOrderCart::findOrFail($data['product_id']);
        $cartProduct->update([
            'qty' => isset($data['qty'])?$data['qty']:1,
            'total_price' => isset($data['qty'])?$data['qty'] * $cartProduct->price:1 * $cartProduct->price
        ]);
        $cartData = $this->getInhouseCartData();
        $this->generateShippingCost($cartData);
        return true;
    }

    public function getPaymentMethods(){
        return PaymentMethod::where('method','Cash On Delivery')->first();
    }

    public function deleteById($id){
        $cart_product =  InhouseOrderCart::findOrFail($id);
        $cart_product->delete();
        $cartData = $this->getInhouseCartData();
        $this->generateShippingCost($cartData);
        return true;
    }

    public function addressSave($data){
        if(Session::has('inhouse_order_shipping_address')){
            Session::forget('inhouse_order_shipping_address');
        }
        if(Session::has('inhouse_order_billing_address')){
            Session::forget('inhouse_order_billing_address');
        }

        $cartData = [];
        $cartData['shipping_name'] = $data['shipping_name'];
        $cartData['shipping_email'] = $data['shipping_email'];
        $cartData['shipping_phone'] = $data['shipping_phone'];
        $cartData['shipping_address'] = $data['shipping_address'];
        $cartData['shipping_city'] = $data['shipping_city'];
        $cartData['shipping_state'] = $data['shipping_state'];
        $cartData['shipping_country'] = $data['shipping_country'];
        $cartData['shipping_postcode'] = $data['shipping_postcode'];
        $cartData['is_bill_address'] = $data['is_bill_address'];

        Session::put('inhouse_order_shipping_address', $cartData);

        if($data['is_bill_address'] == 1){
            $shippingData = [];
            $shippingData['billing_name'] = $data['billing_name'];
            $shippingData['billing_email'] = $data['billing_email'];
            $shippingData['billing_phone'] = $data['billing_phone'];
            $shippingData['billing_address'] = $data['billing_address'];
            $shippingData['billing_city'] = $data['billing_city'];
            $shippingData['billing_state'] = $data['billing_state'];
            $shippingData['billing_country'] = $data['billing_country'];
            $shippingData['billing_postcode'] = $data['billing_postcode'];

            Session::put('inhouse_order_billing_address', $shippingData);
        }

        return true;

    }

    public function resetAddress(){
        if(Session::has('inhouse_order_shipping_address')){
            Session::forget('inhouse_order_shipping_address');
        }
        if(Session::has('inhouse_order_billing_address')){
            Session::forget('inhouse_order_billing_address');
        }

        return true;
    }

    public function store($data){

        $query = InhouseOrderCart::where('is_select', 1)->where('user_id', auth()->id())->get();

        $recs = new \Illuminate\Database\Eloquent\Collection($query);
        $grouped = $recs->groupBy('seller_id');

        $customer_email =  session()->get('inhouse_order_shipping_address')['shipping_email'];
        $customer_phone =  session()->get('inhouse_order_shipping_address')['shipping_phone'];



        $order = Order::create([
            'customer_id' =>  null,
            'order_number' => rand(11,99).date('ymdhis'),
            'payment_type' => $data['payment_method'],
            'order_type' => 'inhouse_order',
            'is_paid' => 0,
            'is_confirmed' => 1,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'customer_shipping_address' => session()->get('inhouse_order_shipping_address')['shipping_state'],
            'customer_billing_address' => session()->get('inhouse_order_billing_address')?session()->get('inhouse_order_billing_address')['billing_state']:session()->get('inhouse_order_shipping_address')['shipping_state'],
            'grand_total' => $data['grand_total'],
            'sub_total' => $data['sub_total'],
            'discount_total' => $data['discount_total'],
            'shipping_total' => $data['shipping_charge'],
            'number_of_package' => $data['number_of_package'],
            'number_of_item' => $data['total_quantity'],
            'order_status' => 0,
            'order_payment_id' =>  null,
            'tax_amount' => $data['gst_tax_total']
        ]);
        $shipping_address = session()->get('inhouse_order_shipping_address');
        GuestOrderDetail::create([
            'order_id' => $order->id,
            'guest_id' => $order->id.date('ymd-his'),
            'shipping_name' => $shipping_address['shipping_name'],
            'shipping_email' => $shipping_address['shipping_email'],
            'shipping_phone' => $shipping_address['shipping_phone'],
            'shipping_address' => $shipping_address['shipping_address'],
            'shipping_city_id' => $shipping_address['shipping_city'],
            'shipping_state_id' => $shipping_address['shipping_state'],
            'shipping_country_id' => $shipping_address['shipping_country'],
            'shipping_post_code' => $shipping_address['shipping_postcode'],

            'billing_name' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_name'] : session()->get('inhouse_order_shipping_address')['shipping_name'],
            'billing_email' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_email'] : session()->get('inhouse_order_shipping_address')['shipping_email'],
            'billing_phone' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_phone'] : session()->get('inhouse_order_shipping_address')['shipping_phone'],
            'billing_address' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_address'] : session()->get('inhouse_order_shipping_address')['shipping_address'],
            'billing_city_id' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_city'] : session()->get('inhouse_order_shipping_address')['shipping_city'],
            'billing_state_id' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_state'] : session()->get('inhouse_order_shipping_address')['shipping_state'],
            'billing_country_id' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_country'] : session()->get('inhouse_order_shipping_address')['shipping_country'],
            'billing_post_code' => (session()->has('inhouse_order_billing_address')) ? session()->get('inhouse_order_billing_address')['billing_postcode'] : session()->get('inhouse_order_shipping_address')['shipping_postcode']
        ]);

        $val = 0;
        foreach($grouped as $key => $packages){
            $seller = User::find($key);
            $index_no = $val + 1;
            $packageData = OrderPackageDetail::create([
                'order_id' => $order->id,
                'seller_id' => $key,
                'package_code' => 'TRK-' . rand(4554555,45754575),
                'number_of_product' => count($packages),
                'shipping_cost' => $data['shipping_cost'][$val],
                'shipping_date' => $data['delivery_date'][$val],
                'shipping_method' => $data['shipping_method'][$val],
                'tax_amount' => $data['packagewiseTax'][$val],
            ]);


            foreach($packages as $key => $product){
                $tax  = 0;
                $seller_state = PickupLocation::pickupPointAddress($seller->id)->state_id;
                
                if (session()->has('inhouse_order_shipping_address') && app('gst_config')['enable_gst'] == "gst"){
                    if($seller_state == $shipping_address['shipping_state']){
                        if($product->product->product->product->gstGroup){
                            $sameStateTaxesGroup = json_decode($product->product->product->product->gstGroup->same_state_gst);
                            $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                            foreach ($sameStateTaxesGroup as $key => $sameStateTax){
                                $gstAmount = $product->total_price * $sameStateTax / 100;
                                $tax += $gstAmount;
                            }
                        }else{
                            $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                            foreach ($sameStateTaxes as $key => $sameStateTax){
                                $gstAmount = $product->total_price * $sameStateTax->tax_percentage / 100;
                                $tax += $gstAmount;
                            }
                        }
                    }else{
                        if($product->product->product->product->gstGroup){
                            $diffStateTaxesGroup = json_decode($product->product->product->product->gstGroup->outsite_state_gst);
                            $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                            foreach ($diffStateTaxesGroup as $key => $diffStateTax){
                                $gstAmount = $product->total_price * $diffStateTax / 100;
                                $tax += $gstAmount;
                            }
                        }else{
                            $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                            foreach ($diffStateTaxes as $key => $diffStateTax){
                                $gstAmount = $product->total_price * $diffStateTax->tax_percentage / 100;
                                $tax += $gstAmount;
                            }
                        }
                    }
                }else{
                    if($product->product->product->product->gstGroup){
                        $flatTaxGroup = json_decode($product->product->product->product->gstGroup->same_state_gst);
                        $flatTaxGroup = (array) $flatTaxGroup;
                        
                        foreach($flatTaxGroup as $sameStateTax){
                            $gstAmount = $product->total_price * $sameStateTax / 100;
                            $tax += $gstAmount;
                        }
                    }else{
                        $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
                        $gstAmount = $product->total_price * $flatTax->tax_percentage / 100;
                        $tax += $gstAmount;;
                    }
                }
                OrderProductDetail::create([
                    'package_id' => $packageData->id,
                    'type' => 'product',
                    'product_sku_id' => $product->product_id,
                    'qty' => $product->qty,
                    'price' => $product->price,
                    'total_price' => $product->total_price,
                    'tax_amount' => $tax
                ]);
            }
            $val ++;
        }

        $ids  = InhouseOrderCart::where('user_id', auth()->id())->pluck('id');
        InhouseOrderCart::destroy($ids);

        if(Session::has('inhouse_order_shipping_address')){
            Session::forget('inhouse_order_shipping_address');
        }
        if(Session::has('inhouse_order_billing_address')){
            Session::forget('inhouse_order_billing_address');
        }
        return true;
    }

    public function inhouseOrderList(){
        return Order::where('order_type', 'inhouse_order')->latest();
    }

}
