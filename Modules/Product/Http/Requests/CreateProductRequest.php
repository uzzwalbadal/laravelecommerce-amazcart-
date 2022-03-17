<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Seller\Entities\SellerProduct;

class CreateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => ['required',Rule::unique('products','product_name')->where(function($q){
                return $q->where('id', '!=', $this->id);
                }),Rule::unique('seller_products', 'product_name')->where(function($q){
                    $seller_id = getParentSellerId();
                    return $q->where('product_id', '!=', $this->id)->where('user_id', $seller_id);
                })
            ],
            'product_type' => 'required',
            'category_ids' => 'required',
            'minimum_order_qty' => 'required',
            'tags' => 'required',
            'discount' => 'required',
            'weight' => 'required_if:is_physical,1',
            'length' => 'required_if:is_physical,1',
            'breadth' => 'required_if:is_physical,1',
            'height' => 'required_if:is_physical,1',
        ];


    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
