<div class="modal fade admin-query" id="single_order_method_change_modal">
    <div class="modal-dialog modal_800px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$row->package_code}} {{__('shipping.shipping')}}</h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="shipping_method_change">
                    <div class="row">
                        <input type="hidden" id="packageId" name="order_id" value="{{$row->id}}">


                        <div class="col-lg-12">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="carrier">{{__('shipping.carrier')}} <span class="text-danger">*</span></label>
                                <select class="primary_select mb-15" id="shipping_carrier" name="carrier">
                                    <option selected disabled value="">{{__('common.select_one')}}</option>
                                    @foreach($carriers as $value)
                                        <option {{$row->carrier_id == $value->id ? 'selected' :''}} value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div id="courier_div" class=" row">
                        @include('shipping::order.components._shipping_change',['carrier'=>$row->carrier])
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <div class="d-flex justify-content-center">
                                <button class="primary-btn semi_large2  fix-gr-bg mr-10"  type="submit"><i class="ti-check"></i>{{__('common.submit')}}</button>
                                <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent" data-dismiss="modal" type="button"><i class="ti-check"></i>{{__('common.cancel') }}</button>
                            </div>
                        </div>
                    </div>



                </form>
            </div>


        </div>
    </div>
</div>
