<div class="col-lg-12">
    <div class="primary_input mb-15">
        <label class="primary_input_label" for="shipping_method">{{__('shipping.shipping_method')}} <span class="text-danger">*</span></label>
        <select class="primary_select mb-15" id="c_shipping_method" name="c_shipping_method">
            @foreach($shipping_methods as $value)
                <option {{$row->shipping_method == $value->id ? 'selected' :''}}  value="{{$value->id}}">{{$value->method_name}} [ {{$value->carrier?'Carrier- '.$value->carrier->name. ',':'' }} {{'Time- '.$value->shipment_time}} {{!empty($value->cost_based_on)?', Cost based on '.$value->cost_based_on.',' :''}} {{'Cost '.$value->cost}} ]</option>
            @endforeach
        </select>
        <span class="text-danger"  id="error_shipping_method"></span>
    </div>
</div>

@if($carrier->type == 'Manual')
    <div class="col-lg-12">
        <div class="primary_input mb-15">
            <label class="primary_input_label" for="tracking_id"> {{__('shipping.tracking_id')}} </label>
            <input class="primary_input_field" id="tracking_id" name="tracking_id" placeholder="{{__('shipping.tracking_id')}}" type="text" value="{{old('tracking_id')}}">
            <span class="text-danger" id="error_tracking_id"></span>
        </div>
    </div>
@else
    @if(isModuleActive('ShipRocket') && $carrier->slug =='Shiprocket' && $carrier->status ==1)
        @if(count($couriers) > 0)
        <div class="col-lg-12">
            <div class="primary_input mb-15">
                <label class="primary_input_label" for="">Filter</label>
                <select class="primary_select mb-15" id="filter">
                    <option selected  value="">{{__('common.select_one')}}</option>
                    <option value="1">Cheapest</option>
                    <option value="2">Fasted</option>
                </select>
            </div>
        </div>
        @endif

        <div class="col-lg-12">
            <label class="primary_input_label" for="">{{__('shipping.shipping')}} <span class="required_mark_theme">*</span></label>
            <ul class="permission_list sms_list" id="courier_data">
                @if(count($couriers) > 0)
                    @foreach($couriers as $c)
                        <li>
                            <label class="primary_checkbox d-flex mr-12 ">
                                <input name="shipping_method" class="shipping_method" type="radio" id="shipping_method" value="{{$c['courier_company_id']}}">
                                <span class="checkmark"></span>
                            </label>
                            <p>{{$c['courier_name']}} (Freight Charges: {{single_price($c['freight_charge'])}}, Estimated Delivery: {{$c['estimated_delivery_days']}} days)</p>
                        </li>
                    @endforeach
                @else
                    No courier available for this shipping.
                @endif
            </ul>
        </div>

        <input type="hidden" id="couriers_data" value="{{json_encode($couriers)}}">
        @endif
 @endif
