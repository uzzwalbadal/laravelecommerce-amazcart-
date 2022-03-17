<?php

namespace Modules\Shipping\Repositories;


use App\Traits\ImageStore;
use Illuminate\Support\Arr;
use Modules\Shipping\Entities\Carrier;
use Modules\Shipping\Entities\SellerWiseCarrierConfig;

class CarrierRepository
{
    use ImageStore;

    public function all()
    {
        $seller_id = getParentSellerId();
        $a_carriers = Carrier::where('type','Automatic');
        $m_carriers = Carrier::where('type','Manual')->where('created_by',$seller_id);
        $carriers = $a_carriers->unionAll($m_carriers)->get();

        if(!isModuleActive('ShipRocket')){
            $carriers =  $carriers->filter(function($item) {
                if($item->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }

        if(sellerWiseShippingConfig(1)['seller_use_shiproket'] == 0 &&  $seller_id != 1 ){
            $carriers =  $carriers->filter(function($item) {
                if($item->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }
        return $carriers;
    }

    public function getActiveAll()
    {
        $seller_id = getParentSellerId();
        $a_carriers = Carrier::where('type','Automatic')->whereHas('carrierConfig',function ($q) use ($seller_id){
            $q->where('seller_id',$seller_id)->where('carrier_status',1);
        });
        $m_carriers = Carrier::where('type','Manual')->where('created_by',$seller_id);
        $carriers = $a_carriers->unionAll($m_carriers)->where('status',1)->get();
        if(!isModuleActive('ShipRocket')){
            $carriers =  $carriers->filter(function($item) {
                if($item->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }
        if(sellerWiseShippingConfig(1)['seller_use_shiproket'] == 0 &&  $seller_id != 1 ){
            $carriers =  $carriers->filter(function($item) {
                if($item->slug != 'Shiprocket'){
                    return $item->id;
                }
            });
        }
        return $carriers;
    }

    public function status(array $data)
    {
        $carrier = $this->find($data['carrier_id']);
        if($carrier->slug == 'Shiprocket'){
            return SellerWiseCarrierConfig::where('id',$data['id'])->update([
                'carrier_status' => $data['status'],
            ]);

        }else{
            return Carrier::where('id',$data['id'])->update([
                'status' => $data['status'],
            ]);
        }

    }

    public function find($id)
    {
        return Carrier::findOrfail($id);
    }

    public function carrier_credentials(array $data)
    {
        $seller_id = getParentSellerId();

        if($data['id'] == 0){
            if (!empty($data['logo'])) {
                $data = Arr::add($data, 'logo_src', $this->CarrierLogo($data['logo'], 36, 120));
            }
            SellerWiseCarrierConfig::create([
                'carrier_id'=>$data['carrier_id'],
                'seller_id'=>$seller_id,
                'email'=>$data['email'],
                'password'=>$data['password'],
                'channel_id'=>$data['channel_id'],
                'logo'=>isset($data['logo_src']) ? $data['logo_src'] : null,
            ]);

        }else{
            if (!empty($data['logo'])) {
                $data = Arr::add($data, 'logo_src', $this->CarrierLogo($data['logo'], 36, 120));
                $carrier_config = SellerWiseCarrierConfig::find($data['id']);
                $this->deleteImage($carrier_config->logo);
            }
            SellerWiseCarrierConfig::where('id',$data['id'])->update([
                'carrier_id'=>$data['carrier_id'],
                'seller_id'=>$seller_id,
                'email'=>$data['email'],
                'password'=>$data['password'],
                'channel_id'=>$data['channel_id'],
                'logo'=>isset($data['logo_src']) ? $data['logo_src'] : null,
            ]);
        }

//        $carrier = $this->find($data['id']);
//        if (!empty($data['logo'])) {
//            $data = Arr::add($data, 'logo_src', $this->CarrierLogo($data['logo'], 36, 120));
//            $this->deleteImage($carrier->logo);
//        }
//        if (!empty($data['logo_src'])) {
//            $carrier->logo = $data['logo_src'];
//        }
//        $carrier->save();
//        foreach ($data['types'] as $key => $type) {
//            $this->overWriteEnvFile($type, $data[$type]);
//        }
    }

    public function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"'.trim($val).'"';
            if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                file_put_contents($path, str_replace(
                    $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                ));
            }
            else{
                file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
            }
        }
    }

    public function create(array $data)
    {
        if (!empty($data['logo'])) {
            $data = Arr::add($data, 'logo_url', $this->CarrierLogo($data['logo'], 36, 120));
        }
        $seller_id = getParentSellerId();
        return Carrier::create([
            'name'=>$data['name'],
            'logo'=>isset($data['logo_url'])?$data['logo_url']:null,
            'slug'=>$data['name'],
            'tracking_url'=>$data['tracking_url'],
            'created_by'=>$seller_id,
        ]);
    }


    public function update(array $data,$id)
    {
        $row = $this->find($id);
        if (!empty($data['logo'])) {
            $data = Arr::add($data, 'logo_url', $this->CarrierLogo($data['logo'], 36, 120));
            if($row->logo){
                $this->deleteImage($row->logo);
            }
        }
        return Carrier::where('id',$id)->update([
            'name'=>$data['name'],
            'logo'=>isset($data['logo_url'])?$data['logo_url']:$row->logo,
            'slug'=>$data['name'],
            'tracking_url'=>$data['tracking_url'],
        ]);
    }

    public function delete(array $data)
    {
        $row = $this->find($data['id']);
        if($row->logo){
            $this->deleteImage($row->logo);
        }
        return $row->delete();
    }
}
