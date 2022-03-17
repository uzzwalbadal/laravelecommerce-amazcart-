@extends('backEnd.master')
@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('modules/ordermanage/css/sale_details.css'))}}" />
@endsection
@section('mainContent')
    <div id="add_product">
        <section class="admin-visitor-area up_st_admin_visitor">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="box_header common_table_header">
                            <div class="main-title d-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ $order->package_code }} order Update </h3>
                            </div>
                        </div>
                    </div>

                        <div class="col-lg-12 student-details">
                            <div class="white_box_50px box_shadow_white" id="printableArea">
                                <form method="POST" action="{{route('shipping.carrier_order_update')}}">
                                    @csrf
                                    <input type="hidden" name="carrier_order_id" value="{{$order->carrier_order_id}}">
                                    <div class="row mt-30">
                                        @if ($order->order->customer_id)
                                            <div class="col-md-6 col-lg-6">
                                                <table class="table-borderless clone_line_table">
                                                    <tr>
                                                        <td><strong>{{__('defaultTheme.billing_info')}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.name')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_name" type="text" value="{{$order->order->billing_address->name}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.email')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_email" type="text" value="{{ $order->order->customer_email }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.phone')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_phone" type="text" value="{{$order->order->customer_phone}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.address')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_address" type="text" value="{{$order->order->billing_address->address}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.postal_code')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_post_code" type="text" value="{{$order->order->billing_address->postal_code}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.country')}}</td>
                                                        <td>
                                                            <select name="customer_billing_country" id="business_country" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($countries as $country)
                                                                    <option {{$order->order->billing_address->country == $country->id?'selected':''}} value="{{$country->name}}">{{$country->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.state')}}</td>
                                                        <td>
                                                            <select name="customer_billing_state" id="business_state" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($states as $key => $state)
                                                                    <option {{$order->order->billing_address->state == $state->id?'selected':''}} value="{{$state->name}}">{{$state->name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{__('common.city')}}</td>
                                                        <td>
                                                            <select name="customer_billing_city" id="business_city" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($cities as $key => $city)
                                                                    <option {{$order->order->billing_address->city == $city->id?'selected':''}} value="{{$city->name}}">{{$city->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>


                                                </table>
                                            </div>
                                        @else
                                            <div class="col-md-6 col-lg-6">
                                                <table class="table-borderless clone_line_table">
                                                    <tr>
                                                        <td><strong>{{__('defaultTheme.billing_info')}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.name')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_name" type="text" value="{{$order->order->guest_info->billing_name}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.email')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_email" type="text" value="{{ $order->order->guest_info->billing_email}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.phone')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_phone" type="text" value="{{$order->order->guest_info->billing_phone}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.address')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_address" type="text" value="{{$order->order->guest_info->billing_address}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.postal_code')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_billing_post_code" type="text" value="{{$order->order->guest_info->billing_post_code}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.country')}}</td>
                                                        <td>
                                                            <select name="customer_billing_country" id="business_country" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($countries as $country)
                                                                    <option {{$order->order->guest_info->billing_country_id == $country->id?'selected':''}} value="{{$country->name}}">{{$country->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{__('common.state')}}</td>
                                                        <td>
                                                            <select name="customer_billing_state" id="business_state" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($states as $key => $state)
                                                                    <option {{$order->order->guest_info->billing_state_id == $state->id?'selected':''}} value="{{$state->name}}">{{$state->name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{__('common.city')}}</td>
                                                        <td>
                                                            <select name="customer_billing_city" id="business_city" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($cities as $key => $city)
                                                                    <option {{$order->order->guest_info->billing_city_id == $city->id?'selected':''}} value="{{$city->name}}">{{$city->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>




                                                </table>
                                            </div>
                                        @endif
                                        @if ($order->order->customer_id)
                                            <div class="col-md-6 col-lg-6">
                                                <table class="table-borderless clone_line_table">
                                                    <tr>
                                                        <td><strong>{{__('defaultTheme.shipping_info')}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.name')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_name" type="text" value="{{$order->order->shipping_address->name}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.email')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_email" type="text" value="{{ $order->order->customer_email }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.phone')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_phone" type="text" value="{{$order->order->customer_phone}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.address')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_address" type="text" value="{{$order->order->shipping_address->address}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.postal_code')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_post_code" type="text" value="{{$order->order->shipping_address->postal_code}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.country')}}</td>
                                                        <td>
                                                            <select name="customer_shipping_country" id="business_country" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($countries as $country)
                                                                    <option {{$order->order->shipping_address->country == $country->id?'selected':''}} value="{{$country->name}}">{{$country->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.state')}}</td>
                                                        <td>
                                                            <select name="customer_shipping_state" id="business_state" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($states as $key => $state)
                                                                    <option {{$order->order->shipping_address->state == $state->id?'selected':''}} value="{{$state->name}}">{{$state->name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{__('common.city')}}</td>
                                                        <td>
                                                            <select name="customer_shipping_city" id="business_city" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($cities as $key => $city)
                                                                    <option {{$order->order->shipping_address->city == $city->id?'selected':''}} value="{{$city->name}}">{{$city->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>


                                                </table>
                                            </div>
                                        @else
                                            <div class="col-md-6 col-lg-6">
                                                <table class="table-borderless clone_line_table">
                                                    <tr>
                                                        <td><strong>{{__('defaultTheme.billing_info')}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.name')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_name" type="text" value="{{$order->order->guest_info->shipping_name}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.email')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_email" type="text" value="{{ $order->order->guest_info->shipping_email}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.phone')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_phone" type="text" value="{{$order->order->guest_info->shipping_phone}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.address')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_address" type="text" value="{{$order->order->guest_info->shipping_address}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.postal_code')}}</td>
                                                        <td>
                                                            <input class="primary_input_field" name="customer_shipping_post_code" type="text" value="{{$order->order->guest_info->shipping_post_code}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('common.country')}}</td>
                                                        <td>
                                                            <select name="customer_shipping_country" id="business_country" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($countries as $country)
                                                                    <option {{$order->order->guest_info->shipping_country_id == $country->id?'selected':''}} value="{{$country->name}}">{{$country->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{__('common.state')}}</td>
                                                        <td>
                                                            <select name="customer_shipping_state" id="business_state" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($states as $key => $state)
                                                                    <option {{$order->order->guest_info->shipping_state_id == $state->id?'selected':''}} value="{{$state->name}}">{{$state->name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{__('common.city')}}</td>
                                                        <td>
                                                            <select name="customer_shipping_city" id="business_city" class="primary_select">
                                                                <option value="" disabled selected>{{__('common.select_one')}}</option>
                                                                @foreach($cities as $key => $city)
                                                                    <option {{$order->order->guest_info->shipping_city_id == $city->id?'selected':''}} value="{{$city->name}}">{{$city->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row mt-30">
                                        <div class="col-12 mt-30">
                                            <div class="QA_section QA_section_heading_custom check_box_table">
                                                <div class="QA_table ">
                                                    <!-- table-responsive -->
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <tr>
                                                                <th scope="col">{{__('common.sl')}}</th>
                                                                <th scope="col">{{__('common.image')}}</th>
                                                                <th scope="col">{{__('common.name')}}</th>
                                                                <th scope="col">{{__('common.quantity')}}</th>
                                                                <th scope="col">{{__('common.price')}}</th>
                                                                <th scope="col">{{__('common.tax')}}</th>
                                                                <th scope="col">{{__('common.total')}}</th>
                                                            </tr>
                                                            @foreach ($order->products as $key => $package_product)
                                                                @if($package_product->type == "gift_card")
                                                                    @continue
                                                                @endif
                                                                <tr>
                                                                    <td>
                                                                        {{ $key + 1 }}
                                                                        <input type="hidden" name="product[]" value="{{$key}}">
                                                                    </td>
                                                                    <td>
                                                                        <div class="product_img_div">
                                                                            @if ($package_product->type == "gift_card")
                                                                                <img src="{{showImage(@$package_product->giftCard->thumbnail_image)}}" alt="#">
                                                                            @else
                                                                                @if (@$package_product->seller_product_sku->sku->product->product_type == 1)
                                                                                    <img src="{{showImage(@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->sku->product->thumbnail_image_source)}}"
                                                                                         alt="#">
                                                                                @else
                                                                                    <img src="{{showImage(@$package_product->seller_product_sku->sku->variant_image?@$package_product->seller_product_sku->sku->variant_image:@$package_product->seller_product_sku->product->thum_img??@$package_product->seller_product_sku->product->product->thumbnail_image_source)}}"
                                                                                         alt="#">
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        @if ($package_product->type == "gift_card")
                                                                            <input name="name[{{$key}}]" type="hidden" value="{{$package_product->giftCard->name}}">
                                                                            <span class="text-nowwrap">{{ @$package_product->giftCard->name }}</span>
                                                                            <span class="text-nowrap">{{substr(@$package_product->giftCard->name,0,22)}} @if(strlen(@$package_product->giftCard->name) > 22)... @endif</span>
                                                                            <a class="green gift_card_div pointer" data-gift-card-id='{{ $package_product->giftCard->id }}' data-qty='{{ $package_product->qty }}' data-customer-mail='{{($order->customer_id) ? $order->customer_email : $order->guest_info->shipping_email}}' data-order-id='{{ $order->id }}'><i class="ti-email mr-1 green"></i>
                                                                                {{($order->gift_card_uses->where('gift_card_id', $package_product->giftCard->id)->first() != null && $order->gift_card_uses->where('gift_card_id', $package_product->giftCard->id)->first()->is_mail_sent) ? "Sent Already" : "Send Code Now"}}
                                                                            </a>
                                                                        @else
                                                                            <input name="name[{{$key}}]" type="hidden" value="{{substr(@$package_product->seller_product_sku->sku->product->product_name,0,22)}}">
                                                                            <input name="sku[{{$key}}]" type="hidden" value="{{$package_product->seller_product_sku->sku->sku}}">
                                                                            <span class="text-nowrap">{{substr(@$package_product->seller_product_sku->sku->product->product_name,0,22)}} @if(strlen(@$package_product->seller_product_sku->sku->product->product_name) > 22)... @endif</span>
                                                                        @endif
                                                                    </td>
                                                                    @if ($package_product->type == "gift_card")
                                                                        <td class="text-nowrap">
                                                                            <input class="primary_input_field" type="text" name="qty[{{$key}}]" value="{{ $package_product->qty }}">
                                                                        </td>
                                                                    @else
                                                                        @if (@$package_product->seller_product_sku->sku->product->product_type == 2)
                                                                            <td class="text-nowrap">
                                                                                <input class="primary_input_field" type="text" name="qty[{{$key}}]" value="{{ $package_product->qty }}">
                                                                                <br>
                                                                                @php
                                                                                    $countCombinatiion = count(@$package_product->seller_product_sku->product_variations);
                                                                                @endphp
                                                                                @foreach (@$package_product->seller_product_sku->product_variations as $key => $combination)
                                                                                    @if ($combination->attribute->name == 'Color')
                                                                                        <div class="box_grid ">
                                                                                            <span>{{ $combination->attribute->name }}:</span><span class='box variant_color' style="background-color:{{ $combination->attribute_value->value }}"></span>
                                                                                        </div>
                                                                                    @else
                                                                                        {{ $combination->attribute->name }}:
                                                                                        {{ $combination->attribute_value->value }}
                                                                                    @endif
                                                                                    @if ($countCombinatiion > $key + 1)
                                                                                        <br>
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>
                                                                        @else
                                                                            <td class="text-nowrap">
                                                                                <input class="primary_input_field" type="text" name="qty[{{$key}}]" value="{{ $package_product->qty }}">
                                                                            </td>
                                                                        @endif
                                                                    @endif

                                                                    <td class="text-nowrap">
                                                                        {{ single_price($package_product->price) }}
                                                                        <input type="hidden" name="price[{{$key}}]" value="{{$package_product->price}}">
                                                                    </td>
                                                                    <td class="text-nowrap">
                                                                        {{ single_price($package_product->tax_amount) }}
                                                                        <input type="hidden" name="tax[{{$key}}]" value="{{$package_product->tax_amount}}">
                                                                    </td>
                                                                    <td class="text-nowrap">
                                                                        {{ single_price($package_product->price * $package_product->qty + $package_product->tax_amount) }}
                                                                        <input type="hidden" name="total[{{$key}}]" value="{{$package_product->price * $package_product->qty + $package_product->tax_amount}}">
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="primary_btn_2 mt-2"><i class="ti-check"></i>{{__("common.update")}} </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('change', '#business_country', function(event){
                    let country = $('#business_country').val();

                    $('#pre-loader').removeClass('d-none');
                    if(country){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-state?country_id=' +country;

                        $('#business_state').empty();

                        $('#business_state').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#business_state').niceSelect('update');
                        $('#business_city').empty();
                        $('#business_city').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#business_city').niceSelect('update');
                        $.get(url, function(data){

                            $.each(data, function(index, stateObj) {
                                $('#business_state').append('<option value="'+ stateObj.id +'">'+ stateObj.name +'</option>');
                            });

                            $('#business_state').niceSelect('update');
                            $('#pre-loader').addClass('d-none');
                        });
                    }
                });

                $(document).on('change', '#business_state', function(event){
                    let state = $('#business_state').val();

                    $('#pre-loader').removeClass('d-none');
                    if(state){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-city?state_id=' +state;

                        $('#business_city').empty();

                        $('#business_city').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#business_city').niceSelect('update');

                        $.get(url, function(data){

                            $.each(data, function(index, cityObj) {
                                $('#business_city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                            });

                            $('#business_city').niceSelect('update');
                            $('#pre-loader').addClass('d-none');
                        });
                    }
                });

            });
        })(jQuery);
    </script>
@endpush
