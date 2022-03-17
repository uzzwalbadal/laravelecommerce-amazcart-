<div id="shipping_methods_{{$package['seller_id']}}" class="modal fade shipping_method_modal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal_header_custom_design">
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div id="list_div" class="col-md-12">
                        <div class="address_list_div" id="address_list_div">
                            <table class="table table-hover tablesaw tablesaw-stack text-center shipping_modal_table">
                                <thead>
                                    <tr class="">
                                        <th>{{__('Estimated Delivery')}}</th>
                                        <th>{{__('Cost')}}</th>
                                        <th>{{__('Carrier')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="cart_table_body">
                                @if($is_physical_count > 0)
                                    @foreach($shipping_methods as $key => $shipping)
                                        @if($shipping->id == 1)
                                            @continue
                                        @endif
                                        @php
                                            $cost = 0;
                                            if($shipping->cost_based_on == 'Price'){
                                                if($package['totalItemPrice'] > 0 && $shipping->cost > 0){
                                                    $cost = ($package['totalItemPrice'] / 100) * $shipping->cost + $package['additional_cost'];
                                                }

                                            }elseif ($shipping->cost_based_on == 'Weight'){
                                                 if($package['totalItemWeight'] > 0 && $shipping->cost > 0){
                                                    $cost = ($package['totalItemWeight'] / 100) * $shipping->cost + $package['additional_cost'];
                                                }
                                            }else{
                                                if($shipping->cost > 0){
                                                    $cost = $shipping->cost + $package['additional_cost'];
                                                }
                                            }
                                        @endphp
                                        <tr class="custom_tr">
                                            <td>
                                                <div class="d-flex align-items-center flex-wrap gap_15">
                                                <label class="cs_checkbox cartv2_check_box">
                                                    <input value="{{$shipping->id}}" {{$package['shipping_id'] == $shipping->id ? 'checked':'' }} name="shipping_method_{{$package['seller_id']}}" class="shipping_input_data shipping_method_select" data-package="{{$package['seller_id']}}" type="radio">
                                                    <span class="checkmark"></span>
                                                </label>

                                                <p class="f_s_14 f_w_400 m-0  selected_shipping_text">Estimated Delivery Time: {{$shipping->shipment_time}}</p>
                                                </div>
                                            </td>
                                            <td><p class=" selected_shipping_text">Shipping: {{single_price($cost)}}</p></td>
                                            <td><p class=" selected_shipping_text">{{$shipping->method_name}}</p></td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>

                            </table>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
