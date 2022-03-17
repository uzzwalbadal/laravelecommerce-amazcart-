@extends('frontend.default.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('frontend/default/css/page_css/checkout.css'))}}" />
@endsection
@section('breadcrumb')
    {{ __('defaultTheme.select_payment') }}
@endsection
@section('title')
    {{ __('defaultTheme.select_payment') }}
@endsection
@section('content')
    @include('frontend.default.partials._breadcrumb')
    <div id="mainDiv">
        <div class="checkout_v3_area">
            <div class="checkout_v3_left d-flex justify-content-end">
                <div class="checkout_v3_inner">
                    <div class="shiping_address_box checkout_form m-0">
                        <div class="billing_address">

                            <div class="row">
                                <div class="col-12">
                                    <div class="shipingV3_info mb_30">
                                        <div class="single_shipingV3_info d-flex align-items-start">
                                            <span>{{__('defaultTheme.contact')}}</span>
                                            <h5 class="m-0 flex-fill">
                                                @if(auth()->check())
                                                    {{auth()->user()->email != null?auth()->user()->email : auth()->user()->phone}}
                                                @else
                                                    {{$address->email}}
                                                @endif
                                            </h5>
                                            <a href="{{url('/checkout')}}" class="edit_info_text">{{__('common.change')}}</a>
                                        </div>
                                        <div class="single_shipingV3_info d-flex align-items-start">
                                            <span>{{__('defaultTheme.ship_to')}}</span>
                                            <h5 class="m-0 flex-fill">{{$address->address}}</h5>
                                            <a href="{{url('/checkout')}}" class="edit_info_text">{{__('common.change')}}</a>
                                        </div>
                                        @if(!isModuleActive('MultiVendor'))
                                            <div class="single_shipingV3_info d-flex align-items-start">
                                                <span>{{__('common.method')}}</span>
                                                <h5 class="m-0 flex-fill">{{$selected_shipping_method->method_name}} - {{single_price($shipping_cost)}}</h5>
                                                <a href="{{url()->previous()}}" class="edit_info_text">{{__('common.change')}}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 mb_10">
                                    <h3 class="check_v3_title2 mb-2 ">{{__('common.payment')}}</h3>
                                    <h6 class="shekout_subTitle_text">{{__('defaultTheme.all_transactions_are_secure_and_encrypted')}}.</h6>
                                </div>
                                <div class="col-12">
                                    <div id="accordion" class="checkout_acc_style1 mb_30" >
                                        @foreach($gateway_activations as $key => $payment)

                                            <div class="card">
                                                <div class="card-header" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                            <label class="primary_bulet_checkbox">
                                                                <input type="radio" name="payment_method" class="payment_method" data-name="{{$payment->method}}" data-id="{{encrypt($payment->id)}}" value="{{$payment->id}}" {{$key == 0?'checked':''}}>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <span>{{$payment->method}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapse{{$key}}" class="collapse {{$key == 0?'show':''}}" aria-labelledby="heading{{$key}}" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @if($payment->method == 'Cash On Delivery')
                                                                <div class="col-lg-12 text-center mt_5 mb_25">
                                                                    <span></span>
                                                                </div>
                                                            @elseif($payment->method == 'Wallet')
                                                                <div class="col-lg-12 text-center mt_5 mb_25">
                                                                    <strong>{{__('common.balance')}}: {{single_price(auth()->user()->CustomerCurrentWalletAmounts)}}</strong>
                                                                    <br>
                                                                    <span></span>
                                                                </div>
                                                            @elseif($payment->method == 'Stripe')
                                                                @include('frontend.default.partials.payments.stripe_payment')
                                                            @elseif(isModuleActive('Bkash') && $payment->method=="Bkash")
                                                                @include('bkash::partials._checkout')

                                                            @elseif(isModuleActive('MercadoPago') && $payment->method=="Mercado Pago")
                                                                @include('mercadopago::partials._checkout')

                                                            @elseif(isModuleActive('SslCommerz') && $payment->method=="SslCommerz")
                                                                @include('sslcommerz::partials._checkout')

                                                            @elseif($payment->method == 'PayPal')
                                                                @include('frontend.default.partials.payments.payment_paypal')
                                                            @elseif($payment->method == 'PayStack')
                                                                @include('frontend.default.partials.payments.paystack_payment')
                                                            @elseif($payment->method == 'RazorPay')
                                                                @include('frontend.default.partials.payments.razor_payment')
                                                            @elseif($payment->method == 'Instamojo')
                                                                @include('frontend.default.partials.payments.instamojo_payment')
                                                            @elseif($payment->method == 'PayTM')
                                                                @include('frontend.default.partials.payments.paytm_payment')
                                                            @elseif($payment->method == 'Midtrans')
                                                                @include('frontend.default.partials.payments.midtrans_payment')
                                                            @elseif($payment->method == 'PayUMoney')
                                                                @include('frontend.default.partials.payments.payumoney_payment')
                                                            @elseif($payment->method == 'JazzCash')
                                                                @include('frontend.default.partials.payments.jazzcash_payment_modal')
                                                            @elseif($payment->method == 'Google Pay')
                                                                <a class="btn_1 pointer d-none" id="buyButton">{{ __('wallet.continue_to_pay') }}</a>
                                                            @elseif($payment->method == 'FlutterWave')
                                                                @include('frontend.default.partials.payments.flutter_payment')
                                                            @elseif($payment->method == 'Bank Payment')
                                                                @include('frontend.default.partials.payments.bank_payment')
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="col-lg-12">

                                    <div class="row">
                                        <div class="col-12 mb_10">
                                            <h3 class="check_v3_title2 mb-2 ">{{__('common.billing_address')}}</h3>
                                        </div>
                                        <div class="col-12">
                                            <div id="accordion2" class="checkout_acc_style1 style2 mb_30" >
                                                <div class="card">
                                                    <div class="card-header" id="headingOne1">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link"  type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo2" aria-expanded="{{$billing_address?'true':'false'}}" aria-controls="collapseTwo2">
                                                                <label class="primary_bulet_checkbox">
                                                                    <input type="radio" name="is_same_billing" value="1" {{$billing_address?'':'checked'}}>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <span>{{__('defaultTheme.same_as_shipping_address')}}</span>
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseTwo2" class="collapse" aria-labelledby="headingTwo2" data-parent="#accordion2">
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header" id="headingTwo1">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo1" aria-expanded="{{$billing_address?'true':'false'}}" aria-controls="collapseTwo" type="button">
                                                            <label class="primary_bulet_checkbox">
                                                                <input type="radio" name="is_same_billing" value="0" {{$billing_address?'checked':''}}>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <span>{{__('defaultTheme.use_a_different_billing_address')}}</span>
                                                        </button>
                                                    </h5>
                                                    </div>
                                                    <div id="collapseTwo1" class="collapse {{$billing_address?'show':''}}" aria-labelledby="headingTwo1" data-parent="#accordion2">
                                                        <div class="card-body">
                                                            <div class="row">

                                                                @if(auth()->check())
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label for="name">{{__('defaultTheme.address_list')}} <span class="text-danger">*</span></label>
                                                                            <select class="form-control nc_select" name="address_id" id="address_id">
                                                                            <option value="0">{{__('defaultTheme.new_address')}}</option>
                                                                                @foreach (auth()->user()->customerAddresses->where('is_shipping_default',0) as $addresss)
                                                                                    <option value="{{$addresss->id}}" @if(isset($shipping_address) && $shipping_address->id == $addresss->id) selected @endif >{{$addresss->address}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <input type="hidden" id="address_id" value="0" name="address_id">
                                                                @endif
                                                                <div class="col-lg-6">
                                                                    <label for="name">{{__('common.name')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_name">{{ $errors->first('name') }}</span>
                                                                    <input class="form-control" type="text" id="name" name="name"
                                                                        placeholder="{{__('common.name')}}" value="{{isset($billing_address)?$billing_address->name:''}}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="name">{{__('common.address')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_address">{{ $errors->first('address') }}</span>
                                                                    <input class="form-control" type="text" id="address" name="address"
                                                                        placeholder="{{__('common.address')}}" value="{{isset($billing_address)?$billing_address->address:''}}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                <label for="name">{{__('common.email')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_email">{{ $errors->first('email') }}</span>
                                                                <input class="form-control" type="text" id="email" name="email"
                                                                    placeholder="{{__('common.email')}}" value="{{isset($billing_address)?$billing_address->email:''}}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                <label for="name">{{__('common.phone')}} <span class="text-danger">*</span></label> <span class="text-danger" id="error_phone">{{ $errors->first('phone') }}</span>
                                                                <input class="form-control" type="text" id="phone" name="phone"
                                                                    placeholder="{{__('common.phone')}}" value="{{isset($billing_address)?$billing_address->phone:''}}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                <label>{{__('common.country')}} <span class="text-red">*</span></label>
                                                                <select class="primary_select nc_select" name="country" id="country" autocomplete="off">
                                                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                                    @foreach ($countries as $key => $country)
                                                                        <option value="{{ $country->id }}" @if(isset($billing_address) && $billing_address->country == $country->id) selected @elseif(!isset($billing_address) && app('general_setting')->default_country == $country->id) selected @endif>{{ $country->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger" id="error_country">{{ $errors->first('country') }}</span>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                <label>{{__('common.state')}} <span class="text-red">*</span></label>
                                                                <select class="primary_select nc_select" name="state" id="state" autocomplete="off">
                                                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                                    @if(app('general_setting')->default_country != null)
                                                                        @foreach ($states as $state)
                                                                            <option value="{{$state->id}}" @if(isset($billing_address) && $billing_address->state == $state->id) selected @elseif(app('general_setting')->default_state == $state->id) selected @endif>{{$state->name}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger" id="error_state">{{ $errors->first('state') }}</span>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                <label>{{__('common.city')}} <span class="text-red">*</span></label>

                                                                <select class="primary_select nc_select" name="city" id="city" autocomplete="off">
                                                                    <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                                                    @foreach ($cities as $city)
                                                                        <option value="{{$city->id}}" @if(isset($billing_address) && $billing_address->city == $city->id) selected @endif>{{$city->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger" id="error_city">{{ $errors->first('city') }}</span>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="postal_code">{{__('common.postal_code')}}</label>
                                                                    <input class="form-control" type="text" id="postal_code" name="postal_code" placeholder="{{__('common.postal_code')}}" value="{{isset($billing_address)?$billing_address->postal_code:''}}">
                                                                </div>
                                                                <input type="hidden" id="token" value="{{csrf_token()}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="check_v3_btns flex-wrap d-flex align-items-center">

                                                    <div id="btn_div">
                                                        @php
                                                            $payment_id = encrypt(0);
                                                            $url = '';
                                                            if(count($gateway_activations) > 0 && $gateway_activations[0]->id == 1 || count($gateway_activations) > 0 && $gateway_activations[0]->id == 2){
                                                                $gateway_id = (count($gateway_activations) > 0)?encrypt($gateway_activations[0]->id):0;
                                                                $url = url('/checkout?').'gateway_id='.$gateway_id.'&payment_id='.$payment_id.'&step=complete_order';
                                                                $pay_now_btn = '<a href="'.$url.'" id="payment_btn_trigger" class="btn_1 m-0 text-uppercase">Pay now</a>';
                                                            }else {
                                                                $method = '';
                                                                if(count($gateway_activations) > 0){
                                                                    $method = $gateway_activations[0]->method;
                                                                }
                                                                $pay_now_btn = '<a href="javascript:void(0)" id="payment_btn_trigger" data-type="'.$method.'" class="btn_1 m-0 text-uppercase">Pay now</a>';
                                                            }
                                                        @endphp


                                                        {!! $pay_now_btn !!}
                                                    </div>
                                                    <input type="hidden" value="{{encrypt(0)}}" id="off_payment_id">
                                                @if(isModuleActive('MultiVendor'))
                                                    <a href="{{url()->previous()}}" class="return_text">{{__('defaultTheme.return_to_information')}}</a>
                                                @else
                                                    <a href="{{url()->previous()}}" class="return_text">{{__('defaultTheme.return_to_shipping')}}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="checkout_v3_right d-flex justify-content-start">
                <div class="order_sumery_box flex-fill">
                    @if(!isModuleActive('MultiVendor'))
                        @php
                            $total = 0;
                            $subtotal = 0;
                            $additional_shipping = 0;
                            $tax = 0;
                            $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                            $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                            $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
                        @endphp
                        @foreach($cartData as $key => $cart)
                            @if($cart->product_type == 'product')
                                <div class="singleVendor_product_lists">
                                    <div class="singleVendor_product_list d-flex align-items-center">
                                        <div class="thumb single_thumb">
                                            <img src="
                                                @if($cart->product->product->product->product_type == 1)
                                                {{asset(asset_path($cart->product->product->product->thumbnail_image_source))}}
                                                @else
                                                {{asset(asset_path(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source))}}
                                                @endif
                                            " alt="">
                                        </div>
                                        <div class="product_list_content">
                                            <h4><a href="{{singleProductURL($cart->product->product->seller->slug, $cart->product->product->slug)}}">{{ \Illuminate\Support\Str::limit(@$cart->product->product->product_name, 28, $end='...') }}</a></h4>
                                            @if($cart->product->product->product->product_type == 2)
                                                @php
                                                    $countCombinatiion = count(@$cart->product->product_variations);
                                                @endphp
                                                <p>
                                                @foreach($cart->product->product_variations as $key => $combination)
                                                    @if($combination->attribute->name == 'Color')
                                                    {{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}
                                                    @else
                                                    {{$combination->attribute->name}}: {{$combination->attribute_value->value}}
                                                    @endif

                                                    @if($countCombinatiion > $key +1)
                                                    ,
                                                    @endif
                                                @endforeach
                                                </p>
                                            @endif
                                            <h5 class="d-flex align-items-center"><span
                                                    class="product_count_text">{{$cart->qty}}<span>x</span></span>{{single_price($cart->price)}}</h5>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $subtotal += $cart->total_price;
                                    $additional_shipping += $cart->product->sku->additional_shipping;
                                @endphp

                                @if (file_exists(base_path().'/Modules/GST/') && $cart->product->product->product->is_physical == 1)
                                    @if ($address && app('gst_config')['enable_gst'] == "gst")
                                        @if (\app\Traits\PickupLocation::pickupPointAddress(1)->state_id == $address->state)
                                            @if($cart->product->product->product->gstGroup)
                                                @php
                                                    $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                                    $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                                @endphp
                                                @foreach ($sameStateTaxesGroup as $key => $sameStateTax)
                                                    @php
                                                        $gstAmount = $cart->total_price * $sameStateTax / 100;
                                                        $tax += $gstAmount;
                                                    @endphp
                                                @endforeach
                                            @else
                                                @foreach ($sameStateTaxes as $key => $sameStateTax)
                                                    @php
                                                        $gstAmount = $cart->total_price * $sameStateTax->tax_percentage / 100;
                                                        $tax += $gstAmount;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        @else
                                            @if($cart->product->product->product->gstGroup)
                                                @php
                                                    $diffStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->outsite_state_gst);
                                                    $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                                                @endphp
                                                @foreach ($diffStateTaxesGroup as $key => $diffStateTax)
                                                    @php
                                                        $gstAmount = $cart->total_price * $diffStateTax / 100;
                                                        $tax += $gstAmount;
                                                    @endphp
                                                @endforeach
                                            @else
                                                @foreach ($diffStateTaxes as $key => $diffStateTax)
                                                    @php
                                                        $gstAmount = $cart->total_price * $diffStateTax->tax_percentage / 100;
                                                        $tax += $gstAmount;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        @endif

                                    @else
                                        @if($cart->product->product->product->gstGroup)
                                            @php
                                                $flatTaxGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                                $flatTaxGroup = (array) $flatTaxGroup;
                                            @endphp
                                            @foreach($flatTaxGroup as $sameStateTax)
                                                @php
                                                    $gstAmount = $cart->total_price * $sameStateTax / 100;
                                                    $tax += $gstAmount;
                                                @endphp
                                            @endforeach
                                        @else
                                            @php
                                                $gstAmount = $cart->total_price * $flatTax->tax_percentage / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endif

                                    @endif

                                @else
                                    
                                    @if($cart->product->product->product->gstGroup)
                                        @php
                                            $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                            $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                        @endphp
                                        @foreach ($sameStateTaxesGroup as $key => $sameStateTax)
                                            @php
                                                $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @else
                                        @foreach ($sameStateTaxes as $key => $sameStateTax)
                                            @php
                                                $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @endif

                                @endif

                            @else
                                <div class="singleVendor_product_lists">
                                    <div class="singleVendor_product_list d-flex align-items-center">
                                        <div class="thumb single_thumb">
                                            <img src="{{asset(asset_path(@$cart->giftCard->thumbnail_image))}}" alt="">
                                        </div>
                                        <div class="product_list_content">
                                            <h4><a href="{{route('frontend.gift-card.show',$cart->giftCard->sku)}}">{{ \Illuminate\Support\Str::limit(@$cart->giftCard->name, 28, $end='...') }}</a></h4>
                                            <h5 class="d-flex align-items-center"><span class="product_count_text" >{{$cart->qty}}<span>x</span></span>{{single_price($cart->price)}}</h5>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $subtotal += $cart->total_price;

                                @endphp
                            @endif
                        @endforeach

                        @php
                            $total = $subtotal + $tax + $shipping_cost;
                        @endphp
                    @endif
                    <h3 class="check_v3_title mb_25">{{__('common.order_summary')}}</h3>
                    @if(isModuleActive('MultiVendor'))
                        @php
                            $total = $total_amount;
                        @endphp
                    @endif
                    <div class="subtotal_lists">
                        <div class="single_total_list d-flex align-items-center">
                            <div class="single_total_left flex-fill">
                                <h4 >{{ __('common.subtotal') }}</h4>
                            </div>
                            <div class="single_total_right">
                                <span>+ {{single_price($subtotal_without_discount)}}</span>
                            </div>
                        </div>
                        <div class="single_total_list d-flex align-items-center flex-wrap">
                            <div class="single_total_left flex-fill">
                                <h4>{{__('common.shipping_charge')}}</h4>
                                <p>{{ __('defaultTheme.package_wise_shipping_charge') }}</p>
                            </div>
                            <div class="single_total_right">
                                <span>+ {{single_price(collect($shipping_cost)->sum())}}</span>
                            </div>
                        </div>
                        <div class="single_total_list d-flex align-items-center flex-wrap">
                            <div class="single_total_left flex-fill">
                                <h4>{{__('common.discount')}}</h4>
                            </div>
                            <div class="single_total_right">
                                <span>- {{single_price($discount)}}</span>
                            </div>
                        </div>
                        <div class="single_total_list d-flex align-items-center flex-wrap">
                            <div class="single_total_left flex-fill">
                                <h4>{{__('common.tax')}}/{{__('gst.gst')}}</h4>
                            </div>
                            <div class="single_total_right">
                                <span>+ {{single_price($tax_total)}}</span>
                            </div>
                        </div>
                        @isset($coupon_amount)
                            <div class="single_total_list d-flex align-items-center flex-wrap">
                                <div class="single_total_left flex-fill">
                                    <h4>{{__('common.coupon')}} {{__('common.discount')}}</h4>
                                </div>
                                <div class="single_total_right">
                                    <span>- {{single_price($coupon_amount)}}</span>
                                </div>
                            </div>
                            @php
                                $total = $total - $coupon_amount;
                            @endphp
                        @endisset
                        <div class="total_amount d-flex align-items-center flex-wrap">
                            <div class="single_total_left flex-fill">
                                <span class="total_text">{{__('common.total')}} (Incl. {{__('common.tax')}}/{{__('gst.gst')}})</span>
                            </div>
                            <div class="single_total_right">
                                <span class="total_text"> <span>{{single_price($total)}}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(document).on('change', 'input[type=radio][name=payment_method]', function(){
                    let method = $(this).data('name');
                    $('#order_payment_method').val($(this).val());
                    let payment_id = $('#off_payment_id').val();
                    let gateway_id = $(this).data('id');
                    let baseUrl = $('#url').val();
                    if(method === 'Cash On Delivery'){
                        var url = baseUrl + '/checkout?gateway_id='+gateway_id+'&payment_id='+payment_id+'&step=complete_order';
                        $('#btn_div').html(`<a href="`+url+`" id="payment_btn_trigger" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    if(method === 'Wallet'){
                        var url = baseUrl + '/checkout?gateway_id='+gateway_id+'&payment_id='+payment_id+'&step=complete_order';
                        $('#btn_div').html(`<a href="`+url+`" id="payment_btn_trigger" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }

                    if(method === 'Stripe'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Stripe" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Bkash'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Bkash" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'SslCommerz'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="SslCommerz" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Mercado Pago'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Mercado Pago" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayPal'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayPal" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayStack'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayStack" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'RazorPay'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="RazorPay" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Instamojo'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Instamojo" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayTM'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayTM" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Midtrans'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Midtrans" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'PayUMoney'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="PayUMoney" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'JazzCash'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="JazzCash" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Google Pay'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Google Pay" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'FlutterWave'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="FlutterWave" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }
                    else if(method === 'Bank Payment'){
                        $('#btn_div').html(`<a href="javascript:void(0)" id="payment_btn_trigger" data-type="Bank Payment" class="btn_1 m-0 text-uppercase">Pay now</a>`);
                    }

                });

                $(document).on('click', '#payment_btn_trigger', function(){
                    let method = $(this).data('type');
                    let is_same_billing = $('input[type=radio][name=is_same_billing]:checked').val();
                    $('#error_name').text('');
                    $('#error_email').text('');
                    $('#error_phone').text('');
                    $('#error_address').text('');
                    $('#error_country').text('');
                    $('#error_state').text('');
                    $('#error_city').text('');
                    let is_true = 0;
                    if(is_same_billing == 0){
                        if($('#name').val() == ''){
                            $('#error_name').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#email').val() == ''){
                            $('#error_email').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#phone').val() == ''){
                            $('#error_phone').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#address').val() == ''){
                            $('#error_address').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#country').val() == ''){
                            $('#error_country').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#state').val() == ''){
                            $('#error_state').text('This Field is Required.');
                            is_true = 1;
                        }
                        if($('#city').val() == ''){
                            $('#error_city').text('This Field is Required.');
                            is_true = 1;
                        }
                        if(is_true === 1){
                            return false;
                        }
                        let data = {
                            address_id: $('#address_id').val(),
                            name: $('#name').val(),
                            email: $('#email').val(),
                            address: $('#address').val(),
                            phone: $('#phone').val(),
                            country: $('#country').val(),
                            state: $('#state').val(),
                            city: $('#city').val(),
                            postal_code: $('#postal_code').val(),
                            _token: $('#token').val()
                        }
                        $.post("{{route('frontend.checkout.billing.address.store')}}",data, function(response){
                            paymentAction(method);
                        }).fail(function(response) {
                            $('#error_name').text(response.responseJSON.errors.name);
                            $('#error_address').text(response.responseJSON.errors.address);
                            $('#error_email').text(response.responseJSON.errors.email);
                            $('#error_phone').text(response.responseJSON.errors.phone);
                            $('#error_country').text(response.responseJSON.errors.country);
                            $('#error_state').text(response.responseJSON.errors.state);
                            $('#error_city').text(response.responseJSON.errors.city);
                            return false;
                        });

                    }else{
                        paymentAction(method);
                    }

                });
                function paymentAction(method){
                    if(method == 'Stripe'){
                        $('#stribe_submit_btn').click();
                    }
                    else if(method == 'PayPal'){
                        $('.paypal_btn').click();
                    }
                    else if(method == 'PayStack'){
                        $('#paystack_btn').click();
                    }
                    else if(method == 'RazorPay'){
                        $('#razorpay_btn').click();
                    }
                    else if(method == 'Instamojo'){
                        $("#instamojo_btn").click();
                    }
                    else if(method == 'PayTM'){
                        $("#paytm_btn").click();
                    }
                    else if(method == 'Midtrans'){
                        $("#midtrans_btn").click();
                    }
                    else if(method == 'PayUMoney'){
                        $("#payumoney_btn").click();
                    }
                    else if(method == 'JazzCash'){
                        $("#jazzcash_btn").click();
                    }
                    else if(method == 'Google Pay'){
                        $("#buyButton").click();
                    }
                    else if(method == 'FlutterWave'){
                        $("#flutterwave_btn").click();
                    }
                    else if(method == 'Bank Payment'){
                        $("#bank_btn").click();
                    }
                    else if(method == 'Bkash'){
                        $("#bKash_button").click();
                    }

                    else if(method == 'SslCommerz'){
                        $("#ssl_commerz_form").submit();
                    }
                    else if(method == 'Mercado Pago'){
                        mercado_field_validate();
                        $("#form-checkout__submit").click();
                    }
                }

                function mercado_field_validate() {
                    let cardholderName = $('#form-checkout__cardholderName').val();
                    let cardholderEmail = $('#form-checkout__cardholderEmail').val();
                    let cardNumber = $('#form-checkout__cardNumber').val();
                    let cardExpirationDate = $('#form-checkout__cardExpirationDate').val();
                    let securityCode = $('#form-checkout__securityCode').val();
                    let installments = $('#form-checkout__installments').val();
                    let identificationType = $('#form-checkout__identificationType').val();
                    let identificationNumber = $('#form-checkout__identificationNumber').val();
                    let issuer = $('#form-checkout__issuer').val();

                    if (cardholderName == null) {
                        toastr.error('Cardholder name required');
                        return false;
                    }
                    if (cardholderEmail == null) {
                        toastr.error('Email required');
                        return false;
                    }
                    if (cardNumber == null) {
                        toastr.error('CardNumber required');
                        return false;
                    }
                    if (cardExpirationDate == null) {
                        toastr.error('Card Expiration Date required');
                        return false;
                    }
                    if (securityCode == null) {
                        toastr.error('Security Code required');
                        return false;
                    }
                    if (installments == null) {
                        toastr.error('Installments required');
                        return false;
                    }
                    if (identificationType == null) {
                        toastr.error('Identification Type required');
                        return false;
                    }
                    if (identificationNumber == null) {
                        toastr.error('Identification Number required');
                        return false;
                    }
                    if (issuer == null) {
                        toastr.error('issuer required');
                        return false;
                    }

                }

                // $(document).on('click', '.payment_accordian', function(event){
                //     var in_find = $(this).data('payment_radio');
                //     console.log(in_find);
                //     $(in_find).click();
                // });

                $(document).on('change', '#address_id', function(event) {
                    let data = {
                        _token:"{{csrf_token()}}",
                        id: $(this).val()
                    }
                    $('#pre-loader').show();
                    $.post("{{route('frontend.checkout.address.billing')}}",data, function(res){
                        $('#pre-loader').hide();
                        let address = res.address;
                        let states = res.states;
                        let cities = res.cities;
                        $('#name').val(address.name);
                        $('#address').val(address.address);
                        $('#email').val(address.email);
                        $('#phone').val(address.phone);
                        $('#postal_code').val(address.postal_code);
                        $('#country').val(address.country);

                        $('#state').empty();
                        $('#state').append(
                            `<option value="">Select from options</option>`
                        );
                        $.each(states, function(index, stateObj) {
                            $('#state').append('<option value="' + stateObj
                                .id + '">' + stateObj.name + '</option>');
                        });
                        $('#state').val(address.state);

                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $.each(cities, function(index, cityObj) {
                            $('#city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                        });
                        $('#city').val(address.city);
                        $('select').niceSelect('update');

                    });
                });

                $(document).on('change', '#country', function(event) {
                    let country = $('#country').val();
                    $('#pre-loader').show();
                    if (country) {
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-state?country_id=' + country;

                        $('#state').empty();

                        $('#state').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#state').niceSelect('update');
                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $('#city').niceSelect('update');
                        $.get(url, function(data) {

                            $.each(data, function(index, stateObj) {
                                $('#state').append('<option value="' + stateObj
                                    .id + '">' + stateObj.name + '</option>');
                            });

                            $('#state').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });

                $(document).on('change', '#state', function(event){
                    let state = $('#state').val();
                    $('#pre-loader').show();
                    if(state){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-city?state_id=' +state;


                        $('#city').empty();
                        $('#city').append(
                            `<option value="">Select from options</option>`
                        );
                        $.get(url, function(data){

                            $.each(data, function(index, cityObj) {
                                $('#city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                            });

                            $('#city').niceSelect('update');
                            $('#pre-loader').hide();
                        });
                    }
                });

            });

        })(jQuery);
    </script>

    @include('frontend.default.partials.payments.google_pay_script')
@endpush
