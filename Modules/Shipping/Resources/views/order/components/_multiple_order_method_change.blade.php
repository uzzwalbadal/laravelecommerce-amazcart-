<div class="modal fade admin-query" id="multiple_order_method_change_modal">
    <div class="modal-dialog modal_800px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('shipping.selected_order_shipping_method_change')}}</h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="multiple_shipping_method_change">
                    <div class="row">
                        <input type="hidden" name="order_ids" id="orderIds">
                        <input type="hidden" name="multiple_order" value="1">
                        <div class="col-lg-12">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="shipping_method">{{__('shipping.shipping_method')}} <span class="text-danger">*</span></label>
                                <select class="primary_select mb-15" id="shipping_method" name="shipping_method">
                                    <option selected disabled value="">{{__('common.select_one')}}</option>
                                    @foreach($shipping_methods as $value)
                                        <option  value="{{$value->id}}">{{$value->method_name}} [ {{$value->carrier?'Carrier- '.$value->carrier->name. ',':'' }} {{'Time- '.$value->shipment_time}} {{!empty($value->cost_based_on)?', Cost based on '.$value->cost_based_on.',' :''}} {{'Cost '.$value->cost}} ]</option>
                                    @endforeach
                                </select>
                                <span class="text-danger"  id="error_shipping_method"></span>
                            </div>
                        </div>
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
