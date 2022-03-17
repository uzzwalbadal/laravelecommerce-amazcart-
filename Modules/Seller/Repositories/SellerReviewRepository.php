<?php
namespace Modules\Seller\Repositories;

use Modules\Review\Entities\SellerReview;

class SellerReviewRepository {

    public function getAll(){
        $seller_id = getParentSellerId();
        return SellerReview::where('seller_id',$seller_id)->where('status', 1)->latest()->get();
    }

}
