<div class="box_header common_table_header">
    <div class="main-title d-md-flex">
        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('shipping.edit_pickup_location') }}</h3>
    </div>
</div>
<form method="POST" enctype="multipart/form-data" id="editForm">
    @csrf
    @method('PUT')
    <div class="white_box p-15 box_shadow_white mb-20">
        <div class="row">
            <input type="hidden" value="{{$row->id}}" id="rowId">
            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="pickup_location"> {{__("shipping.pickup_location")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="pickup_location" id="pickup_location" placeholder="{{__("shipping.pickup_location")}}" type="text" value="{{$row->pickup_location}}">
                    <span class="text-danger" id="error_pickup_location"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="name"> {{__("common.name")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="name" id="name" placeholder="{{__("common.name")}}" type="text" value="{{$row->name}}">
                    <span class="text-danger" id="error_name"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="email"> {{__("common.email")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="email" id="email" placeholder="{{__("common.email")}}" type="text" value="{{$row->email}}">
                    <span class="text-danger" id="error_email"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="phone"> {{__("common.phone")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="phone" id="phone" placeholder="{{__("common.phone")}}" type="text" value="{{$row->phone}}">
                    <span class="text-danger" id="error_phone"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="address"> {{__("common.address")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="address" id="address" placeholder="{{__("common.address")}}" type="text" value="{{$row->address}}">
                    <span class="text-danger" id="error_address"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="address_2"> {{__("shipping.address_2")}} </label>
                    <input class="primary_input_field" name="address_2" id="address_2" placeholder="{{__("shipping.address_2")}}" type="text" value="{{$row->address_2}}">
                    <span class="text-danger" id="error_address_2"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="pin_code"> {{__("shipping.pin_code")}} <span class="text-danger">*</span></label>
                    <input class="primary_input_field" name="pin_code" id="pin_code" placeholder="{{__("shipping.pin_code")}}" type="text" value="{{$row->pin_code}}">
                    <span class="text-danger" id="error_pin_code"></span>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="business_country">{{__('seller.country_region')}} <span class="text-danger">*</span></label>
                    <select name="country_id" id="business_country" class="primary_select mb-25">
                        <option value="" disabled selected>{{__('common.select_one')}}</option>
                        @foreach($countries as $country)
                            <option {{$row->country_id == $country->id ?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="error_country_id"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="business_state">{{__('common.state')}} <span class="text-danger">*</span></label>
                    <select name="state_id" id="business_state" class="primary_select mb-25">
                        <option value="" disabled selected>{{__('common.select_one')}}</option>
                        @foreach($states as $key => $state)
                            <option {{$row->state_id== $state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="error_state_id"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="primary_input mb-15">
                    <label class="primary_input_label" for="business_city">{{__('common.city')}} <span class="text-danger">*</span></label>
                    <select name="city_id" id="business_city" class="primary_select mb-25">
                        <option value="" disabled selected>{{__('common.select_one')}}</option>
                        @foreach($cities as $key => $city)
                            <option {{$row->city_id == $city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="error_city_id"></span>
                </div>
            </div>

            {{--            <div class="col-lg-12">--}}
            {{--                <div class="primary_input mb-15">--}}
            {{--                    <label class="primary_input_label" for="lat"> {{__("shipping.latitude")}} </label>--}}
            {{--                    <input class="primary_input_field" name="lat" id="lat" placeholder="{{__("shipping.latitude")}}" type="text" value="{{old('lat')}}">--}}
            {{--                    <span class="text-danger" id="error_lat"></span>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            {{--            <div class="col-lg-12">--}}
            {{--                <div class="primary_input mb-15">--}}
            {{--                    <label class="primary_input_label" for="long"> {{__("shipping.longitude")}} </label>--}}
            {{--                    <input class="primary_input_field" name="long" id="long" placeholder="{{__("shipping.longitude")}}" type="text" value="{{old('long')}}">--}}
            {{--                    <span class="text-danger" id="error_lat"></span>--}}
            {{--                </div>--}}
            {{--            </div>--}}


            <div class="col-lg-12 text-center">
                <button class="primary_btn_2 mt-2"><i class="ti-check"></i>{{__("common.update")}} </button>
            </div>
        </div>
    </div>
</form>

