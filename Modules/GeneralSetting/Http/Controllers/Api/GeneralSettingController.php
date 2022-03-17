<?php

namespace Modules\GeneralSetting\Http\Controllers\Api;

use App\Models\User;
use App\Traits\Otp;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\GeneralSetting\Repositories\CurrencyRepository;
use Modules\GeneralSetting\Transformers\OTPConfigurationResource;
use Modules\Language\Repositories\LanguageRepository;
use Modules\Otp\Entities\OtpConfiguration;

/**
* @group General Setting
*
* APIs for General Setting
*/
class GeneralSettingController extends Controller
{
    use Otp;

    protected $languageRepo;

    public function __construct(LanguageRepository $languageRepo)
    {
        $this->languageRepo = $languageRepo;
    }

    /**
     * Settings info
     * @response{
     *      "settings": [
     *           {
     *               "site_title": "Amaz cart",
     *               "company_name": "Amaz cart",
     *               "country_name": "BD",
     *               "zip_code": "1200",
     *               "address": "Panthapath",
     *               "phone": "0187595662",
     *               "email": "amazcart@spondonit.com",
     *               "currency_symbol": "$",
     *               "logo": "uploads/settings/6127358234608.png",
     *               "favicon": "uploads/settings/6127304e2f2b6.png",
     *               "currency_code": "USD",
     *               "copyright_text": "Copyright © 2019 - 2020 All rights reserved | This application is made by <a href=\"https://codecanyon.net/user/codethemes\">Codethemes</a>",
     *               "language_code": "en"
     *           }
     *       ],
     *       "msg": "success"
     * }
     */
    public function index(){
        $settings = DB::table('general_settings')->select('site_title', 'company_name','country_name', 'zip_code','address','phone','email','currency_symbol','logo','favicon','currency_code','copyright_text','language_code','country_id','state_id','city_id')->first();
        $currencyRepo = new CurrencyRepository();
        $currencies = $currencyRepo->getActiveAll();
        $languages = $this->languageRepo->getActiveAll();
        $vendorType = 'single';
        $otp_configuration = null;
        if(isModuleActive('Otp')){
            $otp_config = OtpConfiguration::whereIn('key', ['code_validation_time', 'otp_activation_for_seller', 'otp_activation_for_customer', 'otp_activation_for_order','order_otp_on_verified_customer','order_cancel_limit_on_verified_customer','otp_on_login','otp_on_password_reset'])->get();
            $otp_configuration = OTPConfigurationResource::collection($otp_config);
        }
        if(isModuleActive('MultiVendor')){
            $vendorType = 'multi';
        }

        return response()->json([
            'settings' => $settings,
            'currencies' => $currencies,
            'languages' => $languages,
            'vendorType' => $vendorType,
            'otp_configuration' => $otp_configuration,
            'msg' => 'success'
        ]);
    }

    /**
     * Languages
     * @response{
     *      "languages": [
     *           {
     *               "id": 3,
     *               "code": "ar",
     *               "name": "Arabic",
     *               "native": "العربية",
     *               "rtl": 1,
     *               "status": 1,
     *               "json_exist": 0,
     *               "created_at": null,
     *               "updated_at": null
     *           },
     *           {
     *               "id": 5,
     *               "code": "az",
     *               "name": "Azerbaijani",
     *               "native": "Azərbaycanca / آذربايجان",
     *               "rtl": 0,
     *               "status": 1,
     *               "json_exist": 0,
     *               "created_at": null,
     *               "updated_at": "2021-09-08T10:40:27.000000Z"
     *           },
     *           {
     *               "id": 9,
     *               "code": "bn",
     *               "name": "Bengali",
     *               "native": "বাংলা",
     *               "rtl": 0,
     *               "status": 1,
     *               "json_exist": 0,
     *               "created_at": null,
     *               "updated_at": "2021-09-09T11:21:10.000000Z"
     *           },
     *           {
     *               "id": 19,
     *               "code": "en",
     *               "name": "English",
     *               "native": "English",
     *               "rtl": 0,
     *               "status": 1,
     *               "json_exist": 0,
     *               "created_at": null,
     *               "updated_at": "2021-09-09T10:11:04.000000Z"
     *           }
     *       ],
     *       "msg": "success"
     * }
     */

    public function getActiveLanguages(){
        $languages = $this->languageRepo->getActiveAll();
        return response()->json([
            'languages' => $languages,
            'msg' => 'success'
        ]);
    }

    public function sendOTPForAPI(Request $request){

        $request->validate([
            'type' => 'required'
        ]);
        if($request->type == 'otp_on_customer_registration'){
            $request->validate([
                'code' => ['required', 'numeric', 'digits:6'],
                'email' => ['required', 'string', 'max:255', 'unique:users,email', 'check_unique_phone'],
                'first_name' => 'required'
            ]);

        }elseif($request->type == 'otp_on_order_with_cod'){
            $request->validate([
                'code' => ['required', 'numeric', 'digits:6'],
                'email' => 'required',
                'name' => 'required',
                'phone' => 'required'
            ]);
        }
        elseif($request->type == 'otp_on_seller_registration'){
            $request->validate([
                'code' => ['required', 'numeric', 'digits:6'],
                'email' => 'required',
                'name' => 'required',
                'phone' => 'required'
            ]);
        }
        elseif($request->type == 'otp_on_login'){
            
            $request->validate([
                'code' => ['required', 'numeric', 'digits:6'],
                'email' => ['required'],
                'password' => ['required', 'string','min:8']
            ]);
            $user = User::where('email', $request->email)->where('is_active', 1)->whereHas('role', function($q){
                $q->where('type', 'customer');
            })->first();
            if(!$user){
                $user = User::where('username', $request->email)->where('is_active', 1)->whereHas('role', function($q){
                    $q->where('type', 'customer');
                })->first();
            }

            if(!$user || !Hash::check($request->password,$user->password)){
                throw ValidationException::withMessages([
                    'email' => __('auth.failed')
                ]);
            }

        }
        elseif($request->type == 'otp_on_password_reset'){
            $request->validate([
                'code' => ['required', 'numeric', 'digits:6'],
                'email' => 'required'
            ]);
            $user = User::where('email', $request->email)->where('is_active', 1)->whereHas('role', function($q){
                $q->where('type', 'customer');
            })->first();
            if(!$user){
                $user = User::where('username', $request->email)->where('is_active', 1)->whereHas('role', function($q){
                    $q->where('type', 'customer');
                })->first();
            }
            if(!$user){
                throw ValidationException::withMessages([
                    'email' => __('auth.failed')
                ]);
            }

        }else{
            throw ValidationException::withMessages([
                'type' => 'invalid type.'
            ]);
        }
        
        $result = $this->sendOTPFromAPI($request);
        if($result){
            return response()->json([
                'msg' => 'success'
            ], 200);
        }else{
            return response()->json([
                'msg' => 'failed'
            ], 403);
        }
    }
}
