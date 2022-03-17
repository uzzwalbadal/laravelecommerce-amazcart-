<?php

namespace Modules\GST\Repositories;
use Modules\GST\Entities\GstTax;

class GstRepository
{
    public function getAllList()
    {
        return GstTax::latest()->get();
    }

    public function getActiveList()
    {
        return GstTax::Active()->get();
    }

    public function create($data)
    {
        return GstTax::create([
                    'name' => $data['name'],
                    'tax_percentage' => $data['rate'],
                    'is_active' => $data['status'],
                ]);
    }

    public function update($data, $id)
    {
        $gst =  GstTax::find($id);
        if($gst){
            return $gst->update([
                'name' => $data['name'],
                'tax_percentage' => $data['rate'],
                'is_active' => $data['status'],
            ]);
        }
        return false;
    }

    public function delete($id)
    {
        $gst_tax = GstTax::findOrFail($id);
        return true;
    }

}
