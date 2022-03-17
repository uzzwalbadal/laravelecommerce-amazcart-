<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeaturedBrandResource;
use App\Http\Resources\FlashDealResource;
use App\Http\Resources\NewUserZoneResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\TopCategoryResource;
use App\Http\Resources\ToppicksResource;
use Illuminate\Http\Request;
use Modules\Appearance\Entities\HeaderSliderPanel;
use Modules\FrontendCMS\Entities\HomePageSection;
use Modules\Marketing\Entities\FlashDeal;
use Modules\Marketing\Entities\NewUserZone;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;

class HomepageController extends Controller
{
    public function index(){
        $categories = Category::with('categoryImage', 'parentCategory', 'subCategories')->where('status', 1)->orderBy('total_sale', 'desc')->take(8)->get();
        $top_categories = TopCategoryResource::collection($categories);
        $brands = Brand::where('status', 1)->where('featured', 1)->latest()->take(20)->get();
        $featured_brands = FeaturedBrandResource::collection($brands);
        $sliders = HeaderSliderPanel::where('status', 1)->where('data_type','!=','url')->orderBy('position')->get();
        $sliders = SliderResource::collection($sliders);
        $new_user_zone = NewUserZone::with('coupon.coupon')->where('status', 1)->first();
        if($new_user_zone){
            $new_user_zone = new NewUserZoneResource($new_user_zone);
        }else{
            $new_user_zone = null;
        }
        $flash_deal = FlashDeal::where('status', 1)->first();
        if($flash_deal){
            $flash_deal = new FlashDealResource($flash_deal);
        }else{
            $flash_deal = null;
        }
        $section = HomePageSection::where('section_name', 'top_picks')->first();
        if($section){
            $top_picks = ToppicksResource::collection($section->getApiProductByQuery());
        }else{
            $top_picks = [];
        }
        return response()->json([
            'top_categories' => $top_categories,
            'featured_brands' => $featured_brands,
            'sliders' => $sliders,
            'new_user_zone' => $new_user_zone,
            "flash_deal" => $flash_deal,
            "top_picks" => $top_picks,
            'msg' => 'success'
        ],200);
    }
}
