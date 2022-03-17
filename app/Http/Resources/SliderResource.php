<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->data_type == 'category'){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'data_type' => $this->data_type,
                'data_id' => $this->data_id,
                'slider_image' => $this->slider_image,
                'position' => $this->position,
                'category' => [
                    'id' => $this->Category->id,
                    'name' => $this->Category->name,
                    'slug' => $this->Category->slug,
                    'icon' => $this->Category->icon,
                ]
            ];
        }
        elseif($this->data_type == 'brand'){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'data_type' => $this->data_type,
                'data_id' => $this->data_id,
                'slider_image' => $this->slider_image,
                'position' => $this->position,
                'brand' => [
                    'id'=> $this->Brand->id,
                    'name' => $this->Brand->name,
                    'logo' => $this->Brand->logo,
                    'slug' => $this->Brand->slug,
                ]
            ];
        }
        elseif($this->data_type == 'product'){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'data_type' => $this->data_type,
                'data_id' => $this->data_id,
                'slider_image' => $this->slider_image,
                'position' => $this->position,
                'product' => $this->Product
            ];
        }
        elseif($this->data_type == 'tag'){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'data_type' => $this->data_type,
                'data_id' => $this->data_id,
                'slider_image' => $this->slider_image,
                'position' => $this->position,
                'tag' => [
                    'id' => $this->Tag->id,
                    'name' => $this->Tag->name
                ]
            ];
        }else{
            return [
            ];
        }
    }
}