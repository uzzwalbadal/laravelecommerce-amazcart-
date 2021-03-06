<?php

namespace Modules\FrontendCMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBenifitRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' =>'required',
            'image' =>'required',
            'description' =>'required',
            'status' =>'required'
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
