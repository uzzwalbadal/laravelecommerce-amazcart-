<?php

namespace Modules\GST\Repositories;

use Modules\GST\Entities\GSTGroup;
use Modules\GST\Entities\GstTax;

class GstConfigureRepository
{
    public function getGroup(){
        return GSTGroup::all();
    }
    public function stoteGroup($data){
        $same_state_gst = [];
        $outsite_state_gst = [];
        foreach($data['same_state_gst'] as $key => $gst){
            $gst = explode('-',$gst);
            $percent = 0;
            if($data['same_state_gst_percent'][$key] != null){
                $percent = $data['same_state_gst_percent'][$key];
            }
            $same_state_gst[$gst[1]] = floatval($percent);
        }

        foreach($data['outsite_state_gst'] as $key => $gst){
            $percent = 0;
            $gst = explode('-',$gst);
            if($data['outsite_state_gst_percent'][$key] != null){
                $percent = $data['outsite_state_gst_percent'][$key];
            }
            $outsite_state_gst[$gst[1]] = floatval($percent);
        }

        GSTGroup::create([
            'name' => $data['name'],
            'same_state_gst' => json_encode($same_state_gst),
            'outsite_state_gst' => json_encode($outsite_state_gst)
        ]);
        return true;
    }

    public function updateGroup($data){
        $same_state_gst = [];
        $outsite_state_gst = [];
        foreach($data['same_state_gst'] as $key => $gst){
            $gst = explode('-',$gst);
            $percent = 0;
            if($data['same_state_gst_percent'][$key] != null){
                $percent = $data['same_state_gst_percent'][$key];
            }
            $same_state_gst[$gst[1]] = floatval($percent);
        }

        foreach($data['outsite_state_gst'] as $key => $gst){
            $percent = 0;
            $gst = explode('-',$gst);
            if($data['outsite_state_gst_percent'][$key] != null){
                $percent = $data['outsite_state_gst_percent'][$key];
            }
            $outsite_state_gst[$gst[1]] = floatval($percent);
        }

        GSTGroup::where('id', $data['id'])->update([
            'name' => $data['name'],
            'same_state_gst' => json_encode($same_state_gst),
            'outsite_state_gst' => json_encode($outsite_state_gst)
        ]);
        return true;
    }

    public function getGroupById($id){
        $group = GSTGroup::where('id', $id)->first();
        if($group){
            return $group;
        }
        return false;
    }

    public function deleteGroupById($id){
        $group = GSTGroup::where('id', $id)->first();
        if(count($group->products) > 0){
            return 'not_posible';
        }else{
            $group->delete();
            return 'posible';
        }
    }

    public function updateConfiguration($data)
    {
        $previousRouteServiceProvier = base_path('Modules/GST/Resources/assets/config_files/config.json');
        $newRouteServiceProvier      = base_path('Modules/GST/Resources/assets/config_files/config.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        $jsonString = file_get_contents(base_path('Modules/GST/Resources/assets/config_files/config.json'));
        $config = json_decode($jsonString, true);
        $config['enable_gst'] = (!empty($data['enable_gst'])) ? $data['enable_gst'] : "0";
        $config['flat_tax_id'] = (!empty($data['flat_tax_id'])) ? $data['flat_tax_id'] : "0";
        foreach ($data as $key => $value) {
            if (is_array($data[$key])) {
                for ($i=0; $i < count($value); $i++) {
                    $config[$key][$i] = $value[$i];
                }
                $newJsonString = json_encode($config, JSON_PRETTY_PRINT);
                $newJsonString2 = json_encode($config[$key] , JSON_PRETTY_PRINT);
                file_put_contents(base_path('Modules/GST/Resources/assets/config_files/config.json'), stripslashes($newJsonString));
            }
        }
    }
}
