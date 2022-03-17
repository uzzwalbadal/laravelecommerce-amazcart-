<?php

namespace Modules\Shipping\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Repositories\LabelConfigRepository;
use Exception;
use Modules\UserActivityLog\Traits\LogActivity;

class LabelConfigController extends Controller
{
    protected $labelConfigRepo;

    public function __construct(LabelConfigRepository $labelConfigRepo)
    {
        $this->labelConfigRepo = $labelConfigRepo;
    }

    public function index()
    {
        try{
            $data['conditions'] = $this->labelConfigRepo->all();
            return view('shipping::label.config',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }

    }


    public function update(Request $request)
    {
        try{
           $this->labelConfigRepo->update($request->except('_token'));
            LogActivity::successLog('Label terms & condition Updated successfully.');
            Toastr::success('Label terms & condition Updated successfully.', 'Success');
            return back();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function conditionDelete($id)
    {
        try{
             $this->labelConfigRepo->conditionDestroy($id);
             return response()->json(['status' =>200]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

}
