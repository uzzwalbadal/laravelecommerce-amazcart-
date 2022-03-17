<?php

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CouponService;
use App\Services\WishlistService;
use App\Traits\ImageStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Customer\Entities\CustomerAddress;
use Modules\Customer\Http\Requests\CreateAddressRequest;
use App\Services\OrderService;

/**
* @group User Management
*
* APIs for User Management
*/
class ProfileController extends Controller
{
    use ImageStore;


    protected $couponService;
    protected $wishlistService;
    protected $orderService;

    public function __construct(CouponService $couponService, WishlistService $wishlistService, OrderService $orderService){
        $this->couponService = $couponService;
        $this->wishlistService = $wishlistService;
        $this->orderService = $orderService;
    }


    /**
     * Customer Profile Update
     * 
     * @bodyParam first_name string required customer first name
     * @bodyParam last_name string required customer last name
     * @bodyParam email string required email or phone from user
     * @bodyParam phone string nullable phone number
     * @bodyParam date_of_birth string nullable customer date of birth
     * @bodyParam description string nullable description
     * 
     * @response 202{
     *   'message' => 'profile updated successfully'
     * }
     */
    public function profileUpdate(Request $request){
        
        $request->validate([
            'first_name' => 'nullable',
            'email' => 'nullable|unique:users,email,'.$request->user()->id
        ]);

        $user=User::find($request->user()->id);
        if($user){
            $data=[
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'description'  => $request->description
             ];

            $user->update($data);
    
            return response()->json([
                'message' => 'profile updated successfully'
            ],202);
        }else{
            return response()->json([
                'message' => 'user not found'
            ],404);
        }
        
    }

    /**
     * Change Profile photo
     * @bodyParam avatar file required profile photo
     * @response 202{
     *  'message' => 'updated successfully'
     * }
     * 
     */

    public function updatePhoto(Request $request){

        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,bmp'
        ]);
        $user=User::find($request->user()->id);
        
        if($user){
            $file = $request->file('avatar');

            if ($request->hasFile('avatar')) {
                $this->deleteImage($user->avatar);
                $data['avatar']=$this->saveImage($file,150,150);
            }
            $user->update([
                'avatar' => $data['avatar']
            ]);
            return response()->json([
                'message' => 'updated successfully'
            ],202);
        }else{
            return response()->json([
                'message' => 'user not found'
            ],404);
        }

    }

    /**
     * Customer Address List
     * @response{
     *  "addresses": [
     *       {
     *           "id": 1,
     *           "customer_id": 5,
     *           "name": "customer 1",
     *           "email": "customer1@gmail.com",
     *           "phone": "016859865968",
     *           "address": "dhaka, bangladesh",
     *           "city": "7291",
     *           "state": "348",
     *           "country": "18",
     *           "postal_code": "6568656",
     *           "is_shipping_default": 1,
     *           "is_billing_default": 0,
     *           "created_at": "2021-05-29T12:06:24.000000Z",
     *           "updated_at": "2021-06-06T11:40:15.000000Z"
     *       }
     *   ],
     *   "message": "success"
     * }
     */

    public function addressList(Request $request){
        $addresses = CustomerAddress::with('getCountry','getState','getCity')->where('customer_id', $request->user()->id)->get();
        if(count($addresses) > 0){
            return response()->json([
                'addresses' => $addresses,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'address not found'
            ],404);
        }
    }

    /**
     * Address store
     * @response 201{
     *      'message' : 'address added successfully'
     * }
     */

    public function addressStore(CreateAddressRequest $request){
        
        $data=[
            'customer_id'=>$request->user()->id,
            'name'=>$request->name,
            'email'=>$request->email,
            'address'=>$request->address,
            'phone'=>$request->phone,
            'city'=>$request->city,
            'state'=>$request->state,
            'country'=>$request->country,
            'postal_code'=>$request->postal_code
        ];
        $customer_address=CustomerAddress::create($data);
        $list=CustomerAddress::where('customer_id',$customer_address->customer_id)->get();
        if(count($list)<=1){
            CustomerAddress::where('id', $customer_address->id)->update([
                'is_shipping_default' => 1,
                'is_billing_default' => 1
            ]);
        }

        return response()->json([
            'message' => 'address added successfully'
        ],201);

    }

    /**
     * Address Update
     * 
     * @response 202{
     *      'message' : 'address updated successfully'
     * }
     * 
     */

    public function addreddUpdate(CreateAddressRequest $request, $id){
        $data=[
            'name'=>$request->name,
            'email'=>$request->email,
            'address'=>$request->address,
            'phone'=>$request->phone,
            'city'=>$request->city,
            'state'=>$request->state,
            'country'=>$request->country,
            'postal_code'=>$request->postal_code
        ];
        
        $address = CustomerAddress::where('id', $id)->where('customer_id', $request->user()->id)->first();
        if($address){
            $address->update($data);
            return response()->json([
                'message' => 'address updated successfully'
            ], 202);
        }else{
            return response()->json([
                'message' => 'address not found'
            ], 404);
        }
    }

    /**
     * Address Delete
     * @bodyParam id number required address id
     * @response 202{
     *     'message' : 'address deleted successfully'
     * }
     * 
     */

    public function deleteAddress(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $address = CustomerAddress::where('id', $request->id)->where('customer_id', $request->user()->id)->first();
        if($address){
            $address->delete();
            return response()->json([
                'message' => 'address deleted successfully'
            ],202);
        }else{
            return response()->json([
                'message' => 'address not found'
            ],404);
        }
    }

    /**
     * Set default shipping address
     * @bodyParam id number required address id
     * @response{
     *      'message' : 'set to default shiiping address'
     * }
     */

    public function defaultShippingAddress(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $address = CustomerAddress::where('id', $request->id)->where('customer_id', $request->user()->id)->first();
        if($address){
            $addresses = CustomerAddress::where('customer_id', $request->user()->id)->where('id','!=', $address->id)->get();
            foreach($addresses as $key => $value){
                $value->update([
                    'is_shipping_default' => 0
                ]);
            }
            $address->update([
                'is_shipping_default' => 1
            ]);
            return response()->json([
                'message' => 'set to default shiiping address'
            ],200);
        }else{
            return response()->json([
                'message' => 'address not found'
            ], 404);
        }
    }

    /**
     * Set default billing address
     * @bodyParam id number required address id
     * 
     * @response{
     *      'message' : 'set to default billing address'
     * }
     */

    public function defaultBillingAddress(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $address = CustomerAddress::where('id', $request->id)->where('customer_id', $request->user()->id)->first();
        if($address){
            $addresses = CustomerAddress::where('customer_id', $request->user()->id)->where('id','!=', $address->id)->get();

            foreach($addresses as $key => $value){
                $value->update([
                    'is_billing_default' => 0
                ]);
            }
            $address->update([
                'is_billing_default' => 1
            ]);

            return response()->json([
                'message' => 'set to default billing address'
            ],200);
        }else{
            return response()->json([
                'message' => 'address not found'
            ], 404);
        }
    }

    /**
     * Customer Get Data
     * @response{
     *      "wallet_running_balance": 699.28,
     *       "wallet_pending_balance": 0,
     *       "total_coupon": 1,
     *       "total_wishlist": 3,
     *       "message": "success"
     * }
     */

    public function getCustomerData(Request $request){
        $wallet_running_balance = $request->user()->CustomerCurrentWalletAmounts;
        $wallet_pending_balance = $request->user()->CustomerCurrentWalletPendingAmounts;

        $total_coupon = count($this->couponService->getAll($request->user()->id));
        $total_wishlist = count($this->wishlistService->getCustomerWishlistForAPI($request->user()->id));
        $cancel_orders = $this->orderService->getNumberOfOrdersCancelled($request->user());
        return response()->json([
            'wallet_running_balance' => $wallet_running_balance,
            'wallet_pending_balance' => $wallet_pending_balance,
            'total_coupon' => $total_coupon,
            'total_wishlist' => $total_wishlist,
            'total_cancel_order' => $cancel_orders,
            'message' => 'success'
        ], 200);

    }


}
