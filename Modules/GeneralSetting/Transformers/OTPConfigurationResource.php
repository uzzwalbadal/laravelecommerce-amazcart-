<?php

namespace Modules\GeneralSetting\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OTPConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        if($this->key == 'otp_activation_for_seller'){
            return [
                'type' => 'otp_on_seller_registration',
                'value' => (int)$this->value
            ];
        }
        if($this->key == 'otp_activation_for_customer'){
            return [
                'type' => 'otp_on_customer_registration',
                'value' => (int)$this->value
            ];
        }
        if($this->key == 'otp_activation_for_order'){
            return [
                'type' => 'otp_on_order_with_cod',
                'value' => (int)$this->value
            ];
        }
        else{
            return [
                'type' => $this->key,
                'value' => (int)$this->value
            ];
        }
    }
}
