<?php

namespace Modules\Shipping\Http\Requests;

// use App\Traits\ValidationMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarrierRequest extends FormRequest
{
    // use ValidationMessage;

    public function rules()
    {
        return [
            'name' =>  ['required',Rule::unique('carriers', 'name')->where(function($q){
                $seller_id = getParentSellerId();
                return $q->where('id', '!=', $this->id)->where('created_by', $seller_id);
            })
            ],
            'tracking_url'=>'nullable',
            'logo'=>'nullable',
        ];

    }

    public function authorize()
    {
        return true;
    }
}
