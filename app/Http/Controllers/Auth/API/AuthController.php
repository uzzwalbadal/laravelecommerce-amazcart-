<?php

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Controller;
use App\Models\SocialProvider;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\Notification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Modules\Affiliate\Repositories\AffiliateRepository;
use Modules\GeneralSetting\Entities\EmailTemplateType;
use Modules\GeneralSetting\Entities\UserNotificationSetting;
use Modules\UserActivityLog\Traits\LogActivity;

/**
* @group User Management
*
* APIs for User Management
*/

class AuthController extends Controller
{
    use Notification;
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login
     * @bodyParam email string required email or phone from user
     * @bodyParam password string required password
     *
     * @response{
     * "user": {
     *       "id": 8,
     *       "first_name": "Hafijur SPN",
     *       "last_name": "21",
     *       "username": null,
     *       "photo": null,
     *       "role_id": 4,
     *       "mobile_verified_at": null,
     *       "email": "spn21@spondonit.com",
     *       "is_verified": 0,
     *       "verify_code": null,
     *       "email_verified_at": null,
     *       "notification_preference": "mail",
     *       "is_active": 1,
     *       "avatar": null,
     *       "phone": null,
     *       "date_of_birth": null,
     *       "description": null,
     *       "secret_login": 0,
     *       "secret_logged_in_by_user": null,
     *       "created_at": "2021-06-09T11:56:56.000000Z",
     *       "updated_at": "2021-06-09T12:29:05.000000Z",
     *       "role": {
     *           "id": 4,
     *           "name": "Customer",
     *           "type": "customer",
     *           "details": null,
     *           "created_at": "2021-05-29T05:26:46.000000Z",
     *           "updated_at": null
     *       }
     *   },
     *   "token": "5|Y6PwOvfBo0W4k04SWlZV8naNLNdmXVOUnRQt4KZg",
     *   "message" : "Successfully logged In"
     * }
     */

    public function login(Request $request){

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->orWhere('username', 'email')->where('is_active', 1)->first();

        if($user && Hash::check($request->password, $user->password) && $user->role->type == 'customer'){

            $token = $user->createToken('my_token')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Successfully logged In'
            ];

            return response($response, 200);
        }else{
            return response([
                'message' => 'Invalid Credintials'
            ],401);
        }

    }

    public function socialLogin(Request $request){
        $request->validate([
            'provider_id' => ['required'],
            'provider_name' => ['required'],
            'name' => ['nullable'],
            'email' => ['nullable'],
            'token' => 'required'
        ]);
        if($request->provider_name == 'google'){
            $res = \Illuminate\Support\Facades\Http::get('https://oauth2.googleapis.com/tokeninfo?id_token='.$request->token);
            if($res->successful()){
                return $this->getTokenBySocial($request);
            }else{
                return response()->json([
                    'message' => 'Invalid token.'
                ], 422);
            }
        }
        elseif($request->provider_name == 'facebook'){
            $res = \Illuminate\Support\Facades\Http::get('https://graph.facebook.com/me?access_token='.$request->token);
            if($res->successful()){
                return $this->getTokenBySocial($request);
            }else{
                return response()->json([
                    'message' => 'Invalid token.'
                ], 422);
            }
        }else{
            return response()->json([
                'message' => 'Invalid provider name.'
            ], 422);
        }
    }

    private function getTokenBySocial($request){
        $provider = SocialProvider::where('provider_id', $request->provider_id)->where('provider_name', $request->provider_name)->first();
        if($provider){
            $user = User::where('id', $provider->user_id)->where('is_active', 1)->first();
            if($user){
                $token = $user->createToken('my_token')->plainTextToken;
                $response = [
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Successfully logged In'
                ];
                return response($response, 200);
            }else{
                return response()->json([
                    'message' => 'Your Account is Disabled.'
                ],422);
            }
        }else{
            $exsist = User::where('email', $request->email)->first();
            if(!$exsist){
                $newUser = User::create([
                    'first_name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make("verystrongpass1234"),
                    'role_id' => 4,
                    'is_verified' => 1,
                    'currency_id' => app('general_setting')->currency,
                    'lang_code' => app('general_setting')->language_code,
                    'currency_code' => app('general_setting')->currency_code,
                ]);
    
                SocialProvider::create([
                    'user_id' => $newUser->id,
                    'provider_id' => $request->provider_id,
                    'provider_name' => $request->provider_name,
                ]);
    
                //affiliate user
                if(isModuleActive('Affiliate')){
                    $affiliateRepo = new AffiliateRepository();
                    $affiliateRepo->affiliateUser($newUser->id);
                }
    
                // User Notification Setting Create
                (new UserNotificationSetting)->createForRegisterUser($newUser->id);
                $this->typeId = EmailTemplateType::where('type', 'register_email_template')->first()->id; //register email templete typeid
                $this->adminNotificationUrl = '/customer/active-customer-list';
                $this->routeCheck = 'cusotmer.list.get-data';
                $this->notificationSend("Register", $newUser->id);

                $token = $newUser->createToken('my_token')->plainTextToken;
                $response = [
                    'user' => $newUser,
                    'token' => $token,
                    'message' => 'Successfully logged In'
                ];
                return response($response, 200);
            }else{
                return response()->json([
                    'message' => 'Email Already Taken By Normal Registration.'
                ],422);
            }
        }
    }

    /**
     * Logout user
     * @response{
     * "message": "Logged out successfully"
     * }
     */

    public function logout(Request $request){

        $user = $request->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response([
            'message' => 'Logged out successfully'
        ],200);
    }

    /**
     * Register Customer
     * @bodyParam first_name string required customer first name
     * @bodyParam last_name string required customer last name
     * @bodyParam email string required email or phone from user
     * @bodyParam referral_code string nullable referral code from another user
     * @bodyParam password string required password
     * @bodyParam password_confirmation string required same as password
     * @bodyParam user_type string required customer
     *
     * @response 201{
     *
     * "user": {
     *       "first_name": "customer 5",
     *       "last_name": null,
     *       "username": null,
     *       "email": "customer5@gmail.com",
     *       "role_id": 4,
     *       "phone": null,
     *       "updated_at": "2021-06-10T11:41:35.000000Z",
     *       "created_at": "2021-06-10T11:41:35.000000Z",
     *       "id": 9
     *   },
     *   "token": "6|PV66uUWWSNzekyWW05XqItqI9ernvqAqEkxbYGh0",
     *   "message" : "Successfully registered"
     *
     * }
     */

    public function register(Request $request){

        $request->validate([
            'first_name' => 'required',
            'email' => ['required', 'string', 'max:255', 'unique:users,email', 'check_unique_phone'],
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required'
        ]);
        if($request->user_type == 'customer'){
            $user = $this->authService->register($request->all());
            $token = $user->createToken('my_token')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Successfully registered'
            ];
            return response()->json($response,201);
        }else{
            $response = [
                'message' => 'invalid Credintials'
            ];

            return response()->json($response, 409);
        }
    }

    /**
     * Change Password
     * @bodyParam old_password string required old password
     * @bodyParam password string required new password
     * @bodyParam password_confirmation string required same as new password
     *
     * @response{
     *     'message' => 'password change successfully'
     * }
     */

    public function changePassword(Request $request){

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = $request->user();
        if($user){
            $response = $this->authService->changePassword($user, $request->only('old_password', 'password'));
            if($response == 1){
                return response()->json([
                    'message' => 'password change successfully'
                ],200);
            }else{
                return response()->json([
                    'message' => 'Invalid Credintials.'
                ],409);
            }
        }else{
            return response()->json([
                'message' => 'user not found'
            ],404);
        }

    }

    /**
     * Get user
     * @response{
     * "user": {
     *       "id": 9,
     *       "first_name": "customer 5",
     *       "last_name": null,
     *       "username": null,
     *       "photo": null,
     *       "role_id": 4,
     *       "mobile_verified_at": null,
     *       "email": "customer5@gmail.com",
     *       "is_verified": 0,
     *       "verify_code": null,
     *       "email_verified_at": null,
     *       "notification_preference": "mail",
     *       "is_active": 1,
     *       "avatar": null,
     *       "phone": null,
     *       "date_of_birth": null,
     *       "description": null,
     *       "secret_login": 0,
     *       "secret_logged_in_by_user": null,
     *       "created_at": "2021-06-10T11:41:35.000000Z",
     *       "updated_at": "2021-06-10T11:41:35.000000Z",
     *       "customer_addresses": []
     *   },
     *   "message": "success"
     * }
     */

    public function getUser(Request $request){

        $user = User::with('customerAddresses','currency','language')->where('id', $request->user()->id)->first();

        if($user){
            return response()->json([
                'user' => $user,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'user not found'
            ],404);
        }
    }

    /**
     * Forgot Password
     * @bodyParam email string required customers email
     * @response{
     *      "message": "Reset password link sent on your email id."
     * }
     */

    public function forgotPasswordAPI(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->where('role_id', 4)->first();
        if($user){
            return $this->forgot($request->all());
        }else{
            return response()->json([
                'message' => 'Customer not found.'
            ], 404);
        }
    }

    private function forgot($user) {

        Password::sendResetLink($user);

        return response()->json(["message" => 'Reset password link sent on your email id.']);
    }

}
