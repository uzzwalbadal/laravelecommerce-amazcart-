<div class="box_header common_table_header">
    <div class="main-title d-md-flex">
        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('shipping.add_new_shipping_rate') }}</h3>
    </div>
</div>
<form method="POST" enctype="multipart/form-data" id="createForm">
    @csrf
    <div class="white_box p-15 box_shadow_white mb-20">
        <div class="row">
            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for=""> {{__("common.name")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="method_name" id="method_name" placeholder=" {{__("common.name")}}" type="text" value="{{old('method_name')}}">
                    <span class="text-danger" id="error_method_name"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="carrier_id">{{ __('shipping.carrier') }}</label>
                    <select class="primary_select mb-15" id="carrier_id" name="carrier_id">
                        <option disabled selected value="">{{__('common.select_one')}}</option>
                        @foreach($carriers as $carrier)
                            <option {{old('carrier_id') == $carrier->id ? 'selected' :''}} value="{{$carrier->id}}">{{$carrier->name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="error_carrier_id"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for=""> {{__("shipping.shipment_time")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="shipment_time" id="shipment_time" placeholder="{{__("shipping.ex: 3-5 days or 6-12 hrs")}}" type="text" value="{{old('shipment_time')}}">
                    <span class="text-danger" id="error_shipment_time"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <label class="primary_input_label" for="">{{__('shipping.cost_based_on')}} <span class="text-danger">*</span></label>
                <ul class="permission_list sms_list">
                    <li>
                        <label class="primary_checkbox d-flex mr-12 ">
                            <input name="cost_based_on" class="cost_based_on" type="radio" id="cost_based_on" value="Price">
                            <span class="checkmark"></span>
                        </label>
                        <p>{{__('shipping.price')}}</p>
                    </li>
                    <li>
                        <label class="primary_checkbox d-flex mr-12 ">
                            <input name="cost_based_on" class="cost_based_on" type="radio" id="cost_based_on" value="Weight">
                            <span class="checkmark"></span>
                        </label>
                        <p>{{__('shipping.weight')}}</p>
                    </li>
                    <li>
                        <label class="primary_checkbox d-flex mr-12 ">
                            <input checked name="cost_based_on" class="cost_based_on" type="radio" id="cost_based_on" value="Flat">
                            <span class="checkmark"></span>
                        </label>
                        <p>{{__('shipping.flat')}}</p>
                    </li>
                </ul>
                <span class="text-danger" id="error_cost_based_on"></span>
            </div>
            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for=""> {{__("shipping.cost")}} <span class="cost_help_label required_mark_theme"></span> <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="cost" id="cost" placeholder="{{__("shipping.cost")}}" type="number" min="0" step="{{step_decimal()}}" value="{{old('cost')}}">
                    <span class="text-danger" id="error_cost"></span>
                </div>
            </div>

            <div class="col-lg-12 text-center">
                <button class="primary_btn_2 mt-2"><i class="ti-check"></i>{{__("common.save")}} </button>
            </div>
        </div>
    </div>
</form>
