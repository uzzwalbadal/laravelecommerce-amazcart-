<?php

namespace Modules\Shipping\Http\Requests;

// use App\Traits\ValidationMessage;
use Illuminate\Foundation\Http\FormRequest;

class ShippingConfigurationRequest extends FormRequest
{
    // use ValidationMessage;

    public function rules()
    {
        return [
            'order_confirm_and_sync'=>'nullable',
            'carrier_show_for_customer'=>'nullable',
        ];

    }

    public function authorize()
    {
        return true;
    }
}
