@extends('frontend.default.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('frontend/default/css/page_css/checkout.css'))}}" />
    <style>
        .cursor_pointer{
            cursor: pointer;
        }
        .input-group {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }
        .form-control {
            border-radius: 0;
            height: 50px;
            margin-bottom: 17px;
            color: #8f8f8f;
            font-weight: 300;
        }
        .input_group_text {
            background-color: #ff0027;
            border-radius: 0;
            border: 1px solid transparent;
            color: #fff;
            font-size: 13px;
            text-transform: capitalize;
            font-weight: 500;
            padding: 13px 30px;
            cursor: pointer;
        }
        .link_style{
            color: inherit!important;
        }
        .link_btn_design{
            font-size: 14px;
            color: #fd0027;
            text-transform: uppercase;
            font-weight: 600;
        }
        .link_btn_design:hover{
            font-size: 14px;
            color: #fd0027;
            text-transform: uppercase;
            font-weight: 600;
        }
        .modal_header_custom_design{
            border-bottom: none!important;
        }
        .cart_table_body{
            margin-top: 25px!important;
        }

        .tablesaw thead tr:first-child th {
             padding: 0 40px;
        }
        .custom_tr{
            padding-top: 10px!important;
        }
    </style>
@endsection
@section('breadcrumb')
    {{ __('defaultTheme.customer_information') }}
@endsection
@section('title')
    {{ __('defaultTheme.checkout') }}
@endsection
@section('content')
    @php
        $postalCodeRequired = false;
        if(isModuleActive('ShipRocket')){
            $postalCodeRequired = true;
        }
    @endphp
    @include('frontend.default.partials._breadcrumb')
    <div id="mainDiv">
        @include('frontend.default.partials._checkout_details')
    </div>
@endsection

@push('scripts')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                $(document).on('click', '.link_btn_design', function(event){
                    shippingAddressDiv();
                });

                function shippingAddressDiv(){
                    let shipping_address_div = $('.shipping_address_div');
                    let shipping_address_edit_div = $('.shipping_address_edit_div');
                    shipping_address_div.toggleClass('d-none');
                    shipping_address_edit_div.toggleClass('d-none');
                }


                $(document).on('click', '#shipping_methods', function(event){
                    let id = $(this).data('target');
                    $('#'+id).modal('show');
                });

                $(document).on('change', '.shipping_method_select', function(event){
                    $('#pre-loader').show();
                    let id = $(this).data('package');
                    let shipping_method = $(this).val();
                    let url = "{{route('frontend.change_shipping_method')}}";
                    let data = {
                        _token:"{{csrf_token()}}",
                        seller:id,
                        shipping_method:shipping_method,
                    }
                    $('#shipping_methods_'+id).modal('hide');
                    $.post(url,data, function(res){
                        $('#mainDiv').html(res);
                        $('select').niceSelect();
                        $('#pre-loader').hide();
                    });
                });






                $(document).on('submit', '#mainOrderForm', function(event){

                    let is_submit = 0;
                    let postalCodeRequired = "{{$postalCodeRequired}}"
                    $('#error_term_check').text('');
                    $('#error_name').text('');
                    $('#error_address').text('');
                    $('#error_email').text('');
                    $('#error_phone').text('');
                    $('#error_country').text('');
                    $('#error_state').text('');
                    $('#error_city').text('');
                    $('#error_postal_code').text('');
                    if(!$('#term_check').is(":checked")){
                        is_submit = 1;
                        $('#error_term_check').text('Please Agree With Terms');
                    }
                    if($('#name').val() == ''){
                        is_submit = 1;
                        $('#error_name').text('This Field Is Required');
                    }
                    if(postalCodeRequired == 1 && $('#postal_code').val() == ''){
                        is_submit = 1;
                        $('#error_postal_code').text('This Field Is Required');
                    }
                    if($('#address').val() == ''){
                        is_submit = 1;
                        $('#error_address').text('This Field Is Required');
                    }
                    if($('#email').val() == ''){
                        is_submit = 1;
                        $('#error_email').text('This Field Is Required');
                    }
                    if($('#phone').val() == ''){
                        is_submit = 1;
                        $('#error_phone').text('This Field Is Required');
                    }
                    if($('#country').val() == ''){
                        is_submit = 1;
                        $('#error_country').text('This Field Is Required');
                    }
                    if($('#state').val() == ''){
                        is_submit = 1;
                        $('#error_state').text('This Field Is Required');
                    }
                    if($('#city').val() == ''){
                        is_submit = 1;
                        $('#error_city').text('This Field Is Required');
                    }
                    if(is_submit === 1){
                        event.preventDefault();
                    }else{

                    }
                });

                $(document).on('change', '#address_id', function(event) {
                    let data = {
                        _token:"{{csrf_token()}}",
                        id: $(this).val()
                    }
                    $('#pre-loader').show();
                    $.post("{{route('frontend.checkout.address.shipping')}}",data, function(res){
                        $('#mainDiv').html(res.MainCheckout);
                        $('select').niceSelect();
                        $('#pre-loader').hide();
                    });
                });

                $(document).on('click', '.coupon_apply_btn', function(event){
                    event.preventDefault();
                    let total = $(this).data('total');
                    couponApply(total);
                });

                function couponApply(total){
                    let coupon_code = $('#coupon_code').val();
                    if(coupon_code){
                        $('#pre-loader').show();

                        let formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('coupon_code', coupon_code);
                        formData.append('shopping_amount', total);
                        $.ajax({
                            url: '{{route('frontend.checkout.coupon-apply')}}',
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function (response) {
                                if(response.error){
                                    toastr.error(response.error,'Error');
                                    $('#pre-loader').hide();
                                }else{
                                    $('#mainDiv').html(response.MainCheckout);
                                    toastr.success("{{__('defaultTheme.coupon_applied_successfully')}}","{{__('common.success')}}");
                                    $('#pre-loader').hide();
                                }
                            },
                            error: function (response) {
                                toastr.error(response.responseJSON.errors.coupon_code)
                                $('#pre-loader').hide();
                            }
                        });
                    }else{
                        toastr.error("{{__('defaultTheme.coupon_field_is_required')}}","{{__('common.error')}}");
                    }
                }
                $(document).on('click', '#coupon_delete', function(event){
                    event.preventDefault();
                    couponDelete();
                });

                function couponDelete(){
                    $('#pre-loader').show();
                    let base_url = $('#url').val();
                    let url = base_url + '/checkout/coupon-delete';
                    $.get(url, function(response) {
                        $('#mainDiv').html(response.MainCheckout);
                        $('#pre-loader').hide();
                        toastr.success("{{__('defaultTheme.coupon_deleted_successfully')}}","{{__('common.success')}}");
                    });
                }

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
@endpush
