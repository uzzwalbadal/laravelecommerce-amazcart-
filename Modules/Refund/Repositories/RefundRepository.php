<?php

namespace Modules\Refund\Repositories;

use App\Models\OrderPackageDetail;
use App\Traits\GoogleAnalytics4;
use Carbon\Carbon;
use App\Models\User;
use Modules\Refund\Entities\RefundProduct;
use Modules\Refund\Entities\RefundRequest;
use Modules\Refund\Entities\RefundRequestDetail;
use Modules\Refund\Entities\RefundState;
use Modules\Refund\Http\Controllers\RefundOrderSyncWithCarrierController;
use Modules\Shipping\Entities\ShippingMethod;
use Modules\Wallet\Entities\BankPayment;
use \Modules\Wallet\Repositories\WalletRepository;
use App\Traits\SendMail;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Modules\MultiVendor\Entities\PackageWiseSellerCommision;
use Modules\Refund\Entities\RefundProcess;

class RefundRepository
{
    use SendMail,GoogleAnalytics4;
    public function getRequestAll()
    {
        return RefundRequest::with('refund_details', 'refund_details.refund_products', 'order')->latest()->get();
    }

    public function getRequestForCustomer()
    {
        return RefundRequest::with('refund_details', 'refund_details.refund_products', 'order')->where('customer_id', auth()->user()->id)->latest()->paginate(3);
    }

    public function getRequestSeller()
    {
        $seller_id = $seller_id = getParentSellerId();
        return RefundRequestDetail::with('refund_request', 'seller', 'refund_products', 'order_package')->where('seller_id', $seller_id)->latest()->get();
    }

    public function store($data, $user)
    {
        $package = OrderPackageDetail::find($data['package_id']);
        $shippingMethod = null;
        if($package){
            $shippingMethod = ShippingMethod::find($package->shipping_method);
        }else{
            Toastr::error('Invalid Request.');
            return redirect()->back();
        }
        //ga4
        if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
            $eData = [
                'name' => 'refund',
                'params' => [
                    "currency" => currencyCode(),
                    "value"=> 1,
                    "transaction_id"=> $data['order_id'],
                    "items" => json_decode($data['e_items']),
                ],
            ];
            $this->postEvent($eData);
        }
        //end ga4

        $total_return_amount = 0;
        $refund_request = new RefundRequest;
        $refund_request->customer_id = $user->id;
        $refund_request->order_id = $data['order_id'];
        $refund_request->refund_method = $data['money_get_method'];
        $refund_request->shipping_method = $data['shipping_way'];
        if ($data['shipping_way'] == "courier") {
            $refund_request->shipping_method_id = $shippingMethod->id;
            $refund_request->pick_up_address_id = $data['pick_up_address_id'];
        } else {
            $refund_request->shipping_method_id = $shippingMethod->id;
            $refund_request->drop_off_address = $data['drop_off_courier_address'];
        }
        $refund_request->additional_info = $data['additional_info'];
        $refund_request->save();
        if ($data['money_get_method'] == "bank_transfer") {
            BankPayment::create([
                'itemable_id' => $refund_request->id,
                'itemable_type' => RefundRequest::class,
                'bank_name' => $data['bank_name'],
                'branch_name' => $data['branch_name'],
                'account_number' => $data['account_no'],
                'account_holder' => $data['account_name'],
            ]);
        }
        foreach ($data['product_ids'] as $key => $send_product_id) {
            $split = explode('-', $send_product_id);
            $package[$key] = $split[0];
            $product[$key] = $split[1];
            $seller[$key] = $split[2];
            $amount[$key] = $split[3];

            $request_detail_info = [
                "refund_request_id" => $refund_request->id,
                "order_package_id" => $package->id,
                "seller_id" => $package->seller->id
            ];
            $refund_request_details = RefundRequestDetail::updateOrCreate($request_detail_info);
            $request_product_info = [
                'refund_request_detail_id' => $refund_request_details->id,
                'seller_product_sku_id' => $product[$key],
                'refund_reason_id' => $data['reason_' . $split[1]],
                'return_qty' =>  $data['qty_' . $split[1]],
                'return_amount' =>  $amount[$key] * $data['qty_' . $split[1]],
            ];
            $request_product = RefundProduct::Create($request_product_info);
            $total_return_amount += $request_product->return_amount;
        }
        $refund_quantity = $refund_request_details->refund_products->sum('return_qty');
        $package_product_qty = $package->products->sum('qty');
        if($refund_quantity == $package_product_qty){
            $refund_request->update([
                'total_return_amount' => $total_return_amount + $package->shipping_cost
            ]);
        }else{
            $refund_request->update([
                'total_return_amount' => $total_return_amount
            ]);
        }


        return true;
    }

    public function findByID($id)
    {
        return RefundRequest::with('refund_details', 'refund_details.refund_products', 'order')->findOrFail($id);
    }

    public function findDetailByID($id)
    {
        return RefundRequestDetail::with('refund_request', 'refund_request.order', 'refund_request.shipping_gateway', 'seller', 'refund_products', 'order_package')->findOrFail($id);
    }

    public function updateRefundRequestByAdmin($data, $id)
    {
        $refund_request = RefundRequest::findOrFail($id);
        $refund_details = $refund_request->refund_details->first();
        if(!isModuleActive('MultiVendor')){
            if ($refund_request->is_refunded == 0 && $data['is_refunded'] == 1) {
                
                $refund_infos['seller_id'] = $refund_details->seller_id;
                $refund_infos['amount'] = $refund_request->total_return_amount;
                $refund_infos['type'] = 'Refund';
                

                if ($refund_request->refund_method == "wallet") {
                    $walletRepo = new WalletRepository;
                    $walletRepo->walletRefundPaymentTransaction($refund_request->id, $refund_infos, $refund_request->customer_id);

                    $this->sendMailTest($refund_request->customer->email, "Refund Money Back to You", "Your Money has been added in your wallet for refund purpose.");
                } else {
                    $walletRepo = new WalletRepository;
                    $walletRepo->walletRefundPaymentTransaction($refund_request->id, $refund_infos, null);
                    $this->sendMailTest($refund_request->customer->email, "Refund Money Back to You", "Your Money has been returned in your provided bank Account for refund purpose.");
                }
                
            }

            if ($refund_request->is_refunded != $data['is_refunded']) {
                if (app('business_settings')->where('type', 'mail_notification')->first()->status == 1) {
                    switch ($data['is_refunded']) {
                        case 0:
                            $this->sendOrderRefundInfoUpdateMail($refund_request->order, 12);
                            break;
                        case 1:
                            $this->sendOrderRefundInfoUpdateMail($refund_request->order, 11);
                            break;
                        default:
                            break;
                    }
                }
            }
            if($refund_details->processing_state != $data['processing_state']){
                $refund_details->processing_state = $data['processing_state'];
                $refund_details->save();
            }
        }

        if ($refund_request->is_confirmed != $data['is_confirmed']) {
            if (app('business_settings')->where('type', 'mail_notification')->first()->status == 1) {
                switch ($data['is_confirmed']) {
                    case 0:
                        $this->sendOrderRefundInfoUpdateMail($refund_request->order, 8);
                        break;
                    case 1:
                        $this->sendOrderRefundInfoUpdateMail($refund_request->order, 9);
                        break;
                    case 2:
                        $this->sendOrderRefundInfoUpdateMail($refund_request->order, 10);
                        break;
                    default:
                        break;
                }
            }
            if(isModuleActive('MultiVendor')){
                if($data['is_confirmed'] == 1){
                    $refund_details->update([
                        'processing_state' => 2
                    ]);
                }
            }
        }
        if ($refund_request->is_completed != $data['is_completed']) {
            if (app('business_settings')->where('type', 'mail_notification')->first()->status == 1) {
                switch ($data['is_completed']) {
                    case 1:
                        $this->sendOrderRefundInfoUpdateMail($refund_request->order, 13);
                        break;
                    default:
                        break;
                }
            }
        }
        $refund_request->is_confirmed = $data['is_confirmed'];
        $refund_request->is_completed = $data['is_completed'];
        if(!isModuleActive('MultiVendor')){
            $refund_request->is_refunded = $data['is_refunded'];
        }
        $refund_request->save();

        if($data['is_confirmed'] == 1 ){
            $refundOrderSyncController = new RefundOrderSyncWithCarrierController();
            $res = $refundOrderSyncController->refundOrderSyncWithCarrier($id);
        }



    }

    public function updateRefundStateBySeller($data, $id)
    {
        $refund = RefundRequestDetail::findOrFail($id);

        $last_refund_process = RefundProcess::orderByDesc('id')->first();

        $refund->update([
            'processing_state' => $data['processing_state']
        ]);

        if(!$refund->refund_request->is_refunded && $data['processing_state'] == $last_refund_process->id){

            $refund_infos['seller_id'] = $refund->seller_id;
            $refund_infos['amount'] = $refund->refund_request->total_return_amount;
            $refund_infos['type'] = 'Refund';
            

            if ($refund->refund_request->refund_method == "wallet") {
                $walletRepo = new WalletRepository;
                $walletRepo->walletRefundPaymentTransaction($refund->refund_request->id, $refund_infos, $refund->refund_request->customer_id);

                $this->sendMailTest($refund->refund_request->customer->email, "Refund Money Back to You", "Your Money has been added in your wallet for refund purpose.");
            } else {
                $walletRepo = new WalletRepository;
                $walletRepo->walletRefundPaymentTransaction($refund->refund_request->id, $refund_infos, null);
                $this->sendMailTest($refund->refund_request->customer->email, "Refund Money Back to You", "Your Money has been returned in your provided bank Account for refund purpose.");
            }
            
            $refund->refund_request->update([
                'is_refunded' => 1,
                'is_completed' => 1
            ]);
        }

        RefundState::create([
            'refund_request_detail_id' => $id,
            'state' => $data['processing_state']
        ]);
        if (app('business_settings')->where('type', 'mail_notification')->first()->status == 1) {
            $this->sendOrderRefundorDeliveryProcessMail(@$refund->refund_request->order, "Modules\Refund\Entities\RefundProcess", $data['processing_state']);
        }
    }

    public function getActiveShippingRate()
    {
        $methods = ShippingMethod::where('request_by_user',1)->where('is_active', 1)->whereHas('carrier', function($q){
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

    public function getRefundCommision($id){
        $seller_id = getParentSellerId();
        $refund_details = $this->findDetailByID($id);
        $sales_commision = PackageWiseSellerCommision::where('package_id', $refund_details->order_package_id)->where('seller_id', $seller_id)->first();
        if($sales_commision){
            return $sales_commision->amount;
        }
        return 0;
    }
}
