<?php

namespace Modules\Shipping\Http\Requests;

// use App\Traits\ValidationMessage;
use Illuminate\Foundation\Http\FormRequest;

class PickupLocationRequest extends FormRequest
{
    // use ValidationMessage;

    public function rules()
    {
        return [
            'pickup_location' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'address_2' => 'nullable',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'pin_code' => 'required',
            'status' => 'nullable',
            'created_by' => 'nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
