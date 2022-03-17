<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WishlistService;
/**
* @group wishlist
*
* APIs for customer WishList
*/
class WishListController extends Controller
{

    protected $wishlistService;

    public function __construct(WishlistService $wishlistService){
        $this->wishlistService = $wishlistService;
    }

    /**
     * Wish list
     * @response{
     * 
     *      "products": {
     *           "4": [
     *               {
     *                   "id": 3,
     *                   "user_id": 5,
     *                   "seller_id": 4,
     *                   "type" : "product",
     *                   "seller_product_id": 3,
     *                   "created_at": "2021-06-09T09:44:39.000000Z",
     *                   "updated_at": "2021-06-09T09:44:39.000000Z",
     *                   "user": {
     *                       "id": 5,
     *                       "first_name": "Customer 1",
     *                       "last_name": null,
     *                       "username": "01729975293",
     *                       "photo": null,
     *                       "role_id": 4,
     *                       "mobile_verified_at": null,
     *                       "email": "customer1@gmail.com",
     *                       "is_verified": 0,
     *                       "verify_code": null,
     *                       "email_verified_at": null,
     *                       "notification_preference": "mail",
     *                       "is_active": 1,
     *                       "avatar": null,
     *                       "phone": "3265865323563565",
     *                       "date_of_birth": null,
     *                       "description": "test",
     *                       "secret_login": 0,
     *                       "secret_logged_in_by_user": null,
     *                       "created_at": "2021-05-29T12:02:52.000000Z",
     *                       "updated_at": "2021-06-06T06:24:30.000000Z"
     *                   },
     *                   "seller": {
     *                       "id": 4,
     *                       "first_name": "Amazcart Ltd",
     *                       "last_name": null,
     *                       "username": "0156356563235",
     *                       "photo": null,
     *                       "role_id": 5,
     *                       "mobile_verified_at": null,
     *                       "email": "amazcart@gmail.com",
     *                       "is_verified": 1,
     *                       "verify_code": "74d68bde279426442de115eb532f9f51a21eb448",
     *                       "email_verified_at": null,
     *                       "notification_preference": "mail",
     *                       "is_active": 1,
     *                       "avatar": null,
     *                       "phone": null,
     *                       "date_of_birth": null,
     *                       "description": null,
     *                       "secret_login": 0,
     *                       "secret_logged_in_by_user": null,
     *                       "created_at": "2021-05-29T07:15:56.000000Z",
     *                       "updated_at": "2021-05-29T07:15:56.000000Z"
     *                   },
     *                   "giftcard": {
     *                       gift card info
     * 
     *                   },
     *                   "product": {
     *                       "id": 3,
     *                       "user_id": 4,
     *                       "product_id": 3,
     *                       "tax": 15,
     *                       "tax_type": "0",
     *                       "discount": 50,
     *                       "discount_type": "1",
     *                       "discount_start_date": "05/01/2021",
     *                       "discount_end_date": "06/30/2021",
     *                       "product_name": "KTM RC 390",
     *                       "slug": "ktm-rc-390-4",
     *                       "thum_img": null,
     *                       "status": 1,
     *                       "stock_manage": 0,
     *                       "is_approved": 0,
     *                       "min_sell_price": 6500,
     *                       "max_sell_price": 6500,
     *                       "total_sale": 1,
     *                       "avg_rating": 0,
     *                       "recent_view": "2021-05-29 16:28:14",
     *                       "created_at": "2021-05-29T10:28:14.000000Z",
     *                       "updated_at": "2021-05-30T04:29:14.000000Z",
     *                       "variantDetails": [],
     *                       "MaxSellingPrice": 6600,
     *                       "hasDeal": {
     *                           "id": 2,
     *                           "flash_deal_id": 1,
     *                           "seller_product_id": 3,
     *                           "discount": 50,
     *                           "discount_type": 1,
     *                           "status": 1,
     *                           "created_at": "2021-06-01T12:56:18.000000Z",
     *                           "updated_at": "2021-06-01T13:08:58.000000Z"
     *                       },
     *                       "rating": 0,
     *                       "product": {
     *                           "id": 3,
     *                           "product_name": "KTM RC 390",
     *                           "product_type": 1,
     *                           "unit_type_id": 1,
     *                           "brand_id": 2,
     *                           "category_id": 5,
     *                           "thumbnail_image_source": "uploads/images/29-05-2021/60b1e99781fbb.png",
     *                           "barcode_type": "C39",
     *                           "model_number": "ktm-rc-390",
     *                           "shipping_type": 0,
     *                           "shipping_cost": 0,
     *                           "discount_type": "1",
     *                           "discount": 0,
     *                           "tax_type": "0",
     *                           "tax": 15,
     *                           "pdf": null,
     *                           "video_provider": "youtube",
     *                           "video_link": null,
     *                           "description": "<p>test product</p>",
     *                           "specification": "<p>test product</p>",
     *                           "minimum_order_qty": 1,
     *                           "max_order_qty": 5,
     *                           "meta_title": null,
     *                           "meta_description": null,
     *                           "meta_image": null,
     *                           "is_physical": 1,
     *                           "is_approved": 1,
     *                           "display_in_details": 1,
     *                           "requested_by": 1,
     *                           "created_by": 1,
     *                           "slug": "ktm-rc-390",
     *                           "updated_by": null,
     *                           "created_at": "2021-05-29T07:13:28.000000Z",
     *                           "updated_at": "2021-05-29T07:13:28.000000Z"
     *                       },
     *                       "skus": [
     *                           {
     *                               "id": 7,
     *                               "user_id": 4,
     *                               "product_id": 3,
     *                               "product_sku_id": "4",
     *                               "product_stock": 0,
     *                               "purchase_price": 0,
     *                               "selling_price": 6600,
     *                               "status": 1,
     *                               "created_at": "2021-05-29T10:28:14.000000Z",
     *                               "updated_at": "2021-05-30T04:32:25.000000Z",
     *                               "product_variations": []
     *                           }
     *                       ],
     *                       "reviews": []
     *                   }
     *               }
     *           ]
     *       },
     *       "message": "success"
     * 
     * }
     */
    public function index(Request $request){

        $products = $this->wishlistService->myWishlistAPI($request->user()->id);
        if(count($products) > 0){
            return response()->json([
                'products' => $products,
                'message' => 'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'wishlist is empty'
            ],404);
        }
    }

    /**
     * Store
     * @bodyParam seller_id integer required seller id
     * @bodyParam seller_product_id integer required seller product id
     * @bodyParam type string required  product or giftcard
     * @response 201{
     *      'message' : 'Product added to wishlist.'
     * }
     */

    public function store(Request $request){
        $request->validate([
            'seller_id' => 'required',
            'seller_product_id' => 'required',
            'type' => 'required'
        ]);

        $product = $this->wishlistService->store($request->except('_token'), $request->user());
        if($product == 1){
            return response()->json([
                'message' => 'Product added to wishlist.'
            ],201);
        }elseif($product == 3){
            return response()->json([
                'message' => 'Product already in wishlist'
            ],409);
        }else{
            return response()->json([
                'message' => 'something gone wrong'
            ],500);
        }
    }

    /**
     * Delete
     * @bodyParam id integer required item id from wish list
     * @response 202{
     *      'message' : 'product removed from wishlist successfully.'
     * }
     */

    public function destroy(Request $request){
        $request->validate([
            'id' => 'required',
            'type' => 'required'
        ]);

        $result = $this->wishlistService->removeForAPI($request->id, $request->type, $request->user()->id);
        if($result){
            return response()->json([
                'message' => 'product removed from wishlist successfully.'
            ],202);
        }else{
            return response()->json([
                'message' => 'product not found'
            ],404);
        }
    }
}
