<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewUserZoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "background_color" => $this->background_color,
            "slug" => $this->slug,
            "banner_image" => $this->banner_image,
            "product_navigation_label" => $this->product_navigation_label,
            "category_navigation_label" => $this->category_navigation_label,
            "coupon_navigation_label" => $this->coupon_navigation_label,
            "product_slogan" => $this->product_slogan,
            "category_slogan" => $this->category_slogan,
            "coupon_slogan" => $this->coupon_slogan,
            "coupon" => [
                "id" => $this->coupon->coupon->id,
                "title" => $this->coupon->coupon->title,
                "coupon_code" => $this->coupon->coupon->coupon_code,
                "start_date" => $this->coupon->coupon->start_date,
                "end_date" => $this->coupon->coupon->end_date,
                "discount" => $this->coupon->coupon->discount,
                "discount_type" =>$this->coupon->coupon->discount_type,
                "minimum_shopping" => $this->coupon->coupon->minimum_shopping,
                "maximum_discount" =>$this->coupon->coupon->maximum_discount
            ],
            'AllProducts' => $this->ProductForAPIHomePage
        ];
    }
}
