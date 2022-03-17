
<div class="modal fade" id="carrier_status_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$row->package_code}} {{__('shipping.carrier_status')}}</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h4>{{$row->package_code}} order shiprocket status is : {{$status}}</h4>
                </div>

            </div>
        </div>
    </div>
</div>

