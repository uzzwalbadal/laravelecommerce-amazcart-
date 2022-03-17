<?php

namespace Modules\Shipping\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Shipping\Http\Requests\ShippingConfigurationRequest;
use Modules\Shipping\Repositories\CarrierRepository;
use Modules\Shipping\Repositories\LabelConfigRepository;
use Modules\Shipping\Repositories\PickupLocationRepository;
use Modules\Shipping\Repositories\ShippingConfigurationRepository;

class ShippingConfigurationController extends Controller
{
    protected $shippingConfigRepo,$labelConfigRepo;


   public function __construct(ShippingConfigurationRepository $shippingConfigRepo,LabelConfigRepository $labelConfigRepo)
   {
       $this->shippingConfigRepo = $shippingConfigRepo;
       $this->labelConfigRepo = $labelConfigRepo;
   }

    public function index()
    {

        try{
            $carrierRepo = new CarrierRepository();
            $data['carriers'] = $carrierRepo->getActiveAll();
            $pickupLocationRepo = new PickupLocationRepository();
            $data['pickup_locations'] = $pickupLocationRepo->getActiveAll();
            $data['conditions'] = $this->labelConfigRepo->all();
            $data['row'] = $this->shippingConfigRepo->sellerConfig();
            return view('shipping::configuration',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function update(ShippingConfigurationRequest $request)
    {

        try{
            DB::beginTransaction();
            $this->labelConfigRepo->update($request->only(['conditionIds','eCondition','conditions']));
            $this->shippingConfigRepo->configuration($request->except(['_token','conditionIds','eCondition','conditions']));
            DB::commit();
            Toastr::success('Shipping Configuration Updated Successfully !');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
}
