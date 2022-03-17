<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
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
            "id"=> $this->id,
            "first_name"=> $this->first_name,
            "last_name"=> $this->last_name,
            "photo"=> $this->photo,
            "avatar"=> $this->avatar,
            "phone"=> $this->phone,
            "slug" => $this->slug,
            "date_of_birth"=> $this->date_of_birth,
            "seller_reviews"=> $this->seller_reviews,
            "description" => $this->SellerAccount->about_seller,
            "seller_account" => $this->SellerAccount,
            "seller_business_information" => $this->seller_business_information,
            "SellerProductsAPI" => $this->SellerProductsAPI
        ];
    }
}
