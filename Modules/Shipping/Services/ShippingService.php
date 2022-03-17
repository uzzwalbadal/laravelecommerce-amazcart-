<?php
namespace Modules\Shipping\Services;

use Illuminate\Support\Facades\Validator;
use \Modules\Shipping\Repositories\ShippingRepository;
use App\Traits\ImageStore;
use Illuminate\Support\Arr;
use Modules\Shipping\Entities\ShippingMethod;

class ShippingService
{
    protected $shippingRepository;

    public function __construct(ShippingRepository  $shippingRepository)
    {
        $this->shippingRepository = $shippingRepository;
    }

    public function getAll()
    {
        return $this->shippingRepository->getAll();
    }

    public function getRequestedSellerOwnShippingMethod()
    {
        return $this->shippingRepository->getRequestedSellerOwnShippingMethod();
    }

    public function getActiveAll()
    {
        return $this->shippingRepository->getActiveAll();
    }

    public function store($data)
    {
        $user_id = getParentSellerId();
        $data['request_by_user'] = $user_id;
        $data['is_approved'] = 1;
        $data['is_active'] = 1;
        return $this->shippingRepository->store($data);
    }

    public function findById($id)
    {
        return $this->shippingRepository->find($id);
    }

    public function update($data, $id)
    {
        return $this->shippingRepository->update($data, $id);
    }

    public function delete($id)
    {
        $shipping = ShippingMethod::find($id);
        if($shipping){
            ImageStore::deleteImage($shipping->logo);
        }
        return $this->shippingRepository->delete($id);
    }

    public function updateStatus($data)
    {
        return $this->shippingRepository->updateStatus($data);
    }

    public function updateApproveStatus($data)
    {
        return $this->shippingRepository->updateApproveStatus($data);
    }

    public function getActiveAllForAPI(){
        return $this->shippingRepository->getActiveAllForAPI();
    }
}
