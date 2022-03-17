<?php

namespace Modules\Shipping\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Shipping\Entities\LabelConfig;

class LabelConfigRepository
{
    public function all()
    {
        $user_id = getParentSellerId();
        return LabelConfig::where('created_by',$user_id)->get();
    }

    public function update(array $data)
    {
        $user_id = getParentSellerId();
        if(isset($data['conditionIds'])){
            foreach ($data['conditionIds'] as $cid){
                if(isset($data['eCondition'][$cid])){
                    LabelConfig::where('id',$cid)->update([
                        'condition'=>$data['eCondition'][$cid],
                        'status'=>1,
                        'created_by'=>$user_id,
                    ]);
                }

            }
        }
        foreach ($data['conditions'] as $condition){
            if(isset($condition['condition'])){
                LabelConfig::create([
                    'condition'=>$condition['condition'],
                    'status'=>1,
                    'created_by'=>$user_id,
                ]);
            }
        }

        return true;

    }


    public function find($id)
    {
        return LabelConfig::find($id);
    }

    public function conditionDestroy($id)
    {
        $row = $this->find($id);
        return $row->delete();
    }

}
