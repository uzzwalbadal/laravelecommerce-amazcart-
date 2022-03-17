<?php

namespace Modules\Shipping\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Http\Requests\CarrierRequest;
use Modules\Shipping\Repositories\CarrierRepository;
use Exception;
use Modules\UserActivityLog\Traits\LogActivity;

class CarrierController extends Controller
{
    protected $carrierRepo;

    public function __construct(CarrierRepository $carrierRepo)
    {
        $this->carrierRepo = $carrierRepo;
    }

    public function index()
    {
        try{
            $data['carriers'] = $this->carrierRepo->all();
            return view('shipping::carriers.index',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }

    }

    public function status(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);
        try {
            $this->carrierRepo->status($request->except('_token'));
            LogActivity::successLog('Carrier activate successful.');
            $data['carriers'] = $this->carrierRepo->all();
            return response()->json([
                'status' => 1,
                'list' => (string)view('shipping::carriers.components._config', $data)
            ]);
        }catch(\Exception $e){

            LogActivity::errorLog($e->getMessage());
            return response()->json(['status' => 0]);
        }
    }


    public function configuration(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        try {
            $this->carrierRepo->carrier_credentials($request->except("_token"));
            LogActivity::successLog('Carrier credential update successful.');
            Toastr::success(__('common.updated_successfully'), __('common.success'));
            return back();
        }catch(\Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'), __('error_message'));
            return redirect()->back();
        }
    }

    public function store(CarrierRequest $request)
    {
        try{
            $this->carrierRepo->create($request->validated());
            return $this->reloadWithData();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    private function reloadWithData($msg_type = null){
        try{
            $data['carriers'] = $this->carrierRepo->all();
            return response()->json([
                'msg_type' => $msg_type,
                'carrier_list' =>  (string)view('shipping::carriers.list',$data),
                'config' =>  (string)view('shipping::carriers.components._config',$data),
            ],200);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }

    public function edit($id)
    {
        try{
            $row = $this->carrierRepo->find($id);
            $msg_type = '';
            if($row->type == 'Automatic'){
                $msg_type = 'Automatic';
            }else{
                $msg_type = 'Manual';
            }
            return response()->json([
                'view' => (string)view('shipping::carriers.edit',compact('row')),
                'msg_type' => $msg_type
            ]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function update(CarrierRequest $request,$id)
    {
        try{
            $this->carrierRepo->update($request->validated(),$id);
            return $this->reloadWithData();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function destroy(Request $request)
    {
        try{
            $row = $this->carrierRepo->find($request->id);
            $all = $this->carrierRepo->all();
            $msg_type = '';
            if($row->type == 'Automatic'){
                $msg_type = 'Automatic';
            }else{
                if(count($all) < 2){
                    $msg_type = 'last_item';
                }elseif(count($row->shippingMethods) > 0){
                    $msg_type = 'has_shipping_method';
                }else{
                    $msg_type = 'deleted';
                }

                if($msg_type == 'deleted'){
                    $this->carrierRepo->delete($request->except('_token'));
                }
            }
            return $this->reloadWithData($msg_type);

        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
}
