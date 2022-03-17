<?php

namespace Modules\Shipping\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GeneralSetting\Repositories\GeneralSettingRepository;
use Modules\Setup\Repositories\CityRepository;
use Modules\Setup\Repositories\CountryRepository;
use Modules\Setup\Repositories\StateRepository;
use Modules\Shipping\Http\Requests\PickupLocationRequest;
use Modules\Shipping\Repositories\PickupLocationRepository;
use Modules\UserActivityLog\Traits\LogActivity;


class PickupLocationController extends Controller
{
    protected $pickupLocationRepo;

    public function __construct(PickupLocationRepository $pickupLocationRepo)
    {
        $this->pickupLocationRepo = $pickupLocationRepo;
    }

    public function index()
    {
        try {
            $data['pickup_locations'] = $this->pickupLocationRepo->all();
            $generalSettingRepo = new GeneralSettingRepository();
            $data['setting'] = $generalSettingRepo->getGeneralInfoDetails();
            $data['countries'] = (new CountryRepository())->getActiveAll();
            $data['states'] = (new StateRepository())->getByCountryId($data['setting']->country_id);
            $data['cities'] = (new CityRepository())->getByStateId($data['setting']->state_id);
            return view('shipping::pickup_locations.index',$data);
        } catch (\Exception $e) {
            Toastr::error(__('common.Something Went Wrong'));
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function store(PickupLocationRequest $request)
    {
        try {
            $this->pickupLocationRepo->create($request->validated());
            LogActivity::successLog('New pickup location added');
            Toastr::success(__('common.added_successfully'),__('common.success'));
            return $this->reloadWithData();
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.Something Went Wrong'));
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }


    public function show($id)
    {
        try {
            $data['row'] = $this->pickupLocationRepo->find($id);
            return view('shipping::pickup_locations.components._show',$data);
        } catch (\Exception $e) {
            Toastr::error(__('common.Something Went Wrong'));
            return response()->json(['error' => $e->getMessage()],503);
        }
    }


    public function edit($id)
    {
        try {
            $data['row'] = $this->pickupLocationRepo->find($id);
            $generalSettingRepo = new GeneralSettingRepository();
            $data['setting'] = $generalSettingRepo->getGeneralInfoDetails();
            $data['countries'] = (new CountryRepository())->getActiveAll();
            $data['states'] = (new StateRepository())->getByCountryId($data['row']->country_id);
            $data['cities'] = (new CityRepository())->getByStateId($data['row']->state_id);
            return view('shipping::pickup_locations.components._edit',$data);
        } catch (\Exception $e) {
            Toastr::error(__('common.Something Went Wrong'));
            return response()->json(['error' => $e->getMessage()],503);
        }
    }


    public function update(PickupLocationRequest $request, $id)
    {
        try {
            $this->pickupLocationRepo->update($request->validated(),$id);
            LogActivity::successLog('Pickup location updated');
            Toastr::success(__('common.updated_successfully'),__('common.success'));
            return $this->reloadWithData();
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.Something Went Wrong'));
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->pickupLocationRepo->delete($request->id);
            LogActivity::successLog('Pickup Location has been destroyed.');
            return $this->reloadWithData();
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.Something Went Wrong'));
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }


    public function status(Request $request)
    {
        try {
            $this->pickupLocationRepo->status($request->except("_token"));
            return 1;
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return 0;
        }
    }

    public function setDefault(Request $request)
    {
        try {
            $this->pickupLocationRepo->setDefault($request->except("_token"));
            return $this->reloadWithData();
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return 0;
        }
    }

    private function reloadWithData(){
        try{
            $res = '';
            if(session()->has('ship_rocket_add_location_res')){
                $res = session()->get('ship_rocket_add_location_res');
            }

            $data['pickup_locations'] = $this->pickupLocationRepo->all();
            $generalSettingRepo = new GeneralSettingRepository();
            $data['setting'] = $generalSettingRepo->getGeneralInfoDetails();
            $data['countries'] = (new CountryRepository())->getActiveAll();
            $data['states'] = (new StateRepository())->getByCountryId($data['setting']->country_id);
            $data['cities'] = (new CityRepository())->getByStateId($data['setting']->state_id);
            return response()->json([
                'TableData' =>  (string)view('shipping::pickup_locations.components._list',$data),
                'createForm' =>  (string)view('shipping::pickup_locations.components._create',$data),
                'ship_rocket_response' =>  $res,
            ],200);
        }catch(\Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }

    public function setPickupLocation($id)
    {
        try{
            $res = $this->pickupLocationRepo->setPickupLocation($id);
            return response()->json(['status' =>200]);

        }catch(\Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }
}
