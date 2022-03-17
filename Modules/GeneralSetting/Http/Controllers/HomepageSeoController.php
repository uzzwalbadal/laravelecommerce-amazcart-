<?php

namespace Modules\GeneralSetting\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GeneralSetting\Repositories\GeneralSettingRepository;
use Modules\UserActivityLog\Traits\LogActivity;

class HomepageSeoController extends Controller
{
    protected $generalSettingRepo;
    public function __construct(GeneralSettingRepository $generalSettingRepo){
        $this->generalSettingRepo = $generalSettingRepo;
    }
    public function index(){
        return view('generalsetting::seo_setup.index');
    }

    public function update(Request $request){
        try{
            $this->generalSettingRepo->HomepageSeoUpdate($request->except('_token'));
            Toastr::success(__('common.updated_successfully'), __('common.success'));
            return redirect()->back();
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.error_message'),__('common.error'));
        }
    }
}
