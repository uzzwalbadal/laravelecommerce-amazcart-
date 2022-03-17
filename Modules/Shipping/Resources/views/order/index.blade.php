@extends('backEnd.master')
@section('styles')
    <style>
        .dashed {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px dashed var(--gradient_1);
        }
    </style>
@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-lg-12 mb-25">
                    <div class="white_box_50px box_shadow_white">
                        <form action="{{route('shipping.pending_orders.index')}}" method="get">
                            <div class="row">
                                <div class="col-lg-3 date-range-block">
                                    <div class="primary_input mb-15 date_range">
                                        <div class="primary_datepicker_input filter">
                                            <label class="primary_input_label" for="date">{{__('common.date')}}</label>
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input autocomplete="off" class="primary_input_field filter_date_input_field" type="text" name="date_range_filter" value="{{!empty($date_range_filter) ? $date_range_filter : ""}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for="carrier">{{ __('shipping.carrier')}} </label>
                                        <select class="primary_select mb-15" id="carrier" name="carrier">
                                            <option value="">{{__('common.select_one')}}</option>
                                            @foreach($carriers as $carrier)
                                                <option {{$carrier->id == $f_carrier ? 'selected' :''}} value="{{$carrier->id}}">{{$carrier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for="shipping_method">{{ __('shipping.method')}} </label>
                                        <select class="primary_select mb-15" id="shipping_method" name="shipping_method">
                                            <option value="">{{__('common.select_one')}}</option>
                                            @foreach($shipping_methods as $method)
                                                <option {{$method->id == $shipping_method ? 'selected' :''}} value="{{$method->id}}">{{$method->method_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for="package_code"> {{__("shipping.tracking_id")}}</label>
                                        <input value="{{!empty($package_code) ? $package_code :''}}" class="primary_input_field" name="package_code" id="package_code" placeholder=" {{__("shipping.tracking_id")}}" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <div class="d-flex justify-content-center">
                                        <button class="primary-btn semi_large2  fix-gr-bg mr-10"  type="submit"><i class="ti-search"></i>{{ __('common.search') }}</button>
                                        <a href="{{route('shipping.pending_orders.index')}}" class="primary-btn semi_large2  fix-gr-bg"  type="button"><i class="ti-reload"></i>{{ __('shipping.reset') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr class="dashed">

                        <div class="row">
                            <div class="col-lg-12">
                                <label class="primary_input_label" for="">{{__('shipping.set_pickup_location')}}</label>
                                    <ul class="permission_list sms_list">
                                        @foreach($pickup_locations as $location)
                                            <li>
                                                <label class="primary_checkbox d-flex mr-12 ">
                                                    <input {{pickupLocationData('id') == $location->id ? 'checked' :'' }} name="pickup_location" class="pickup_location" type="radio" id="set_pickup_location" value="{{$location->id}}">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <p>{{$location->pickup_location}}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table mt-80">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="">
                                <table class="table Crm_table_active3">
                                    <thead>
                                    <tr>
                                        <th>{{__('common.sl')}}</th>
                                        <th>{{__('common.date')}}</th>
                                        <th>{{__('common.order_id')}}</th>
                                        <th>{{__('shipping.tracking_id')}}</th>
                                        <th>{{__('shipping.shipping_method')}}</th>
                                        <th>{{__('shipping.carrier')}}</th>
                                        <th>{{__('shipping.packaging')}}</th>
                                        <th>{{__('common.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $key => $row)
                                        <tr>

                                            <td>{{ $key+1 }}</td>
                                            <td>{{ showDate($row->created_at) }}</td>
                                            <td>{{ $row->order->order_number }}</td>
                                            <td>{{ $row->package_code }}</td>
                                            <td>{{$row->shipping->method_name}}</td>
                                            <td>{{$row->carrier->name}}</td>
                                            @php
                                                $packaging_info = false;
                                                if($row->length && $row->breadth && $row->height && $row->weight){
                                                  $packaging_info = true;
                                                }

                                            @endphp
                                            <td>
                                                @if($packaging_info)
                                                {{$row->weight}} - {{$row->length}} x {{$row->breadth}} x {{$row->height}}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{__('common.select')}}
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                                                        @if (permissionCheck('shipping.label_generate'))
                                                            <a target="_blank" href="{{route('shipping.label_generate',$row->id)}}" class="dropdown-item">{{__('shipping.label')}}</a>
                                                        @endif
                                                        @if (permissionCheck('shipping.invoice_generate'))
                                                            <a target="_blank" href="{{route('shipping.invoice_generate',$row->id)}}" class="dropdown-item">{{__('shipping.invoice')}}</a>
                                                        @endif
                                                        @if (permissionCheck('shipping.method_update') && $row->carrier_order_id == null && $packaging_info)
                                                            <a href="#" data-id="{{$row->id}}" class="change_shipping_method dropdown-item">{{__('shipping.shipping')}}</a>
                                                        @endif
{{--                                                        @if (permissionCheck('shipping.method_update') && $row->carrier_order_id && $packaging_info)--}}
{{--                                                            <a href="#" data-id="{{$row->id}}" class="update_carrier_order dropdown-item">{{__('common.update')}}</a>--}}
{{--                                                        @endif--}}
                                                        @if (permissionCheck('shipping.packaging.update'))
                                                            <a href="#" data-id="{{$row->id}}" class="packaging_edit dropdown-item">{{__('shipping.packaging')}}</a>
                                                        @endif
                                                        @if (permissionCheck('shipping.customer_address_update') && $row->carrier_order_id == null)
                                                            <a href="#" data-id="{{$row->id}}" class="customer_address_edit dropdown-item">{{__('common.address')}}</a>
                                                        @endif
                                                        @if (permissionCheck('shipping.carrier_status') && $row->carrier_order_id && $row->carrier->slug == 'Shiprocket')
                                                            <a href="#" data-id="{{$row->id}}" class="carrier_status dropdown-item">{{__('shipping.carrier_status')}}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="append_html"></div>
        @include('shipping::order.components._multiple_order_method_change')
        <input type="hidden" value="{{count($orders)}}" id="total_order">
        <input type="hidden" value="{{route('shipping.single_order_method_change',':id')}}" id="shipping_method_change_url">
        <input type="hidden" value="{{route('shipping.method_update')}}" id="shipping_method_update_url">
        <input type="hidden" value="{{route('shipping.pickup_locations.set',':location')}}" id="set_pickup_location_url">
        <input type="hidden" value="{{route('shipping.update_carrier_order',':id')}}" id="update_carrier_order_url">
        <input type="hidden" value="{{route('shipping.packaging.edit',':id')}}" id="packaging_edit_url">
        <input type="hidden" value="{{route('shipping.packaging.update')}}" id="packaging_update_url">
        <input type="hidden" value="{{route('shipping.carrier_change')}}" id="shipping_carrier_change">
        <input type="hidden" value="{{route('shipping.customer_address_edit',':id')}}" id="customer_address_edit">
        <input type="hidden" value="{{route('shipping.customer_address_update')}}" id="customer_address_update">
        <input type="hidden" value="{{route('shipping.carrier_status',':id')}}" id="carrier_status_url">
    </section>
@endsection
@push('scripts')
    <script src="{{asset('Modules/Shipping/Resources/assets/js/shipping.js')}}"></script>
    <script src="{{asset('Modules/Shipping/Resources/assets/js/shipping_method_change.js')}}"></script>
    <script src="{{asset('Modules/Shipping/Resources/assets/js/date_range.js')}}"></script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $(document).on('change', '#b_business_country', function(event){
                    let country = $('#b_business_country').val();

                    $('#pre-loader').removeClass('d-none');
                    if(country){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-state?country_id=' +country;

                        $('#b_business_state').empty();

                        $('#b_business_state').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#b_business_state').niceSelect('update');
                        $('#b_business_city').empty();
                        $('#b_business_city').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#b_business_city').niceSelect('update');
                        $.get(url, function(data){

                            $.each(data, function(index, stateObj) {
                                $('#b_business_state').append('<option value="'+ stateObj.id +'">'+ stateObj.name +'</option>');
                            });

                            $('#b_business_state').niceSelect('update');
                            $('#pre-loader').addClass('d-none');
                        });
                    }
                });
                $(document).on('change', '#b_business_state', function(event){
                    let state = $('#b_business_state').val();

                    $('#pre-loader').removeClass('d-none');
                    if(state){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-city?state_id=' +state;

                        $('#b_business_city').empty();

                        $('#b_business_city').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#b_business_city').niceSelect('update');

                        $.get(url, function(data){

                            $.each(data, function(index, cityObj) {
                                $('#b_business_city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                            });

                            $('#b_business_city').niceSelect('update');
                            $('#pre-loader').addClass('d-none');
                        });
                    }
                });

                $(document).on('change', '#s_business_country', function(event){
                    let country = $('#s_business_country').val();

                    $('#pre-loader').removeClass('d-none');
                    if(country){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-state?country_id=' +country;

                        $('#s_business_state').empty();

                        $('#s_business_state').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#s_business_state').niceSelect('update');
                        $('#s_business_city').empty();
                        $('#s_business_city').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#s_business_city').niceSelect('update');
                        $.get(url, function(data){

                            $.each(data, function(index, stateObj) {
                                $('#s_business_state').append('<option value="'+ stateObj.id +'">'+ stateObj.name +'</option>');
                            });

                            $('#s_business_state').niceSelect('update');
                            $('#pre-loader').addClass('d-none');
                        });
                    }
                });
                $(document).on('change', '#s_business_state', function(event){
                    let state = $('#s_business_state').val();

                    $('#pre-loader').removeClass('d-none');
                    if(state){
                        let base_url = $('#url').val();
                        let url = base_url + '/seller/profile/get-city?state_id=' +state;

                        $('#s_business_city').empty();

                        $('#s_business_city').append(
                            `<option value="" disabled selected>{{__('common.select_one')}}</option>`
                        );
                        $('#s_business_city').niceSelect('update');

                        $.get(url, function(data){

                            $.each(data, function(index, cityObj) {
                                $('#s_business_city').append('<option value="'+ cityObj.id +'">'+ cityObj.name +'</option>');
                            });

                            $('#s_business_city').niceSelect('update');
                            $('#pre-loader').addClass('d-none');
                        });
                    }
                });

                $(document).on('change', '#shipping_carrier', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    let carrier = $(this).val();
                    let url =  $('#shipping_carrier_change').val();
                    url = url.replace(':id',carrier);
                    let data = {
                        "_token":"{{csrf_token()}}",
                        "carrier_id":carrier,
                        "package_id":$('#packageId').val(),
                    }
                    $.post(url,data, function(response){
                        if(response){
                           $('#courier_div').html(response)
                            $('select').niceSelect();
                            $('#pre-loader').addClass('d-none');

                        }
                    });
                });

                $(document).on('click', '.update_carrier_order', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    let id = $(this).data('id');
                    let url =  $('#update_carrier_order_url').val();
                    url = url.replace(':id',id);
                    $.get(url, function(response){
                        if(response){
                            $('#pre-loader').addClass('d-none');
                            if(response.status == 'NEW'){
                                let url = "{{ route('shipping.edit_carrier_order',':id') }}";
                                url = url.replace(':id', id);
                                document.location.href=url;
                            }else {
                                toastr.warning('Order Update Not Possible')
                            }

                        }
                    });
                });

                $(document).on('change', '#set_pickup_location', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    let location = $('#set_pickup_location:checked').val();
                    let url =  $('#set_pickup_location_url').val();
                    url = url.replace(':location',location);
                    $.get(url, function(response){
                        if(response){
                            $('#pre-loader').addClass('d-none');
                           toastr.success('Pickup Location Change Successfully.')
                        }
                    });
                });

                $(document).on('change', '#filter', function(event){
                    let filter = $(this).val();
                    let couriers = JSON.parse( $('#couriers_data').val());

                    if(filter == "1"){
                        couriers.sort(function(a, b) {
                            return a['freight_charge'] - b['freight_charge'];
                        });
                    }else {
                        couriers.sort(dynamicAlphabeticallySort("estimated_delivery_days"));
                    }
                    let data = ``;

                    $.each(couriers, function (index, c) {
                        data+= ` <li>
                                    <label class="primary_checkbox d-flex mr-12 ">
                                        <input name="shipping_method" class="shipping_method" type="radio" id="shipping_method" value="`+c.courier_company_id+`">
                                        <span class="checkmark"></span>
                                    </label>
                                    <p>`+c.courier_name+` (Freight Charges: `+c.freight_charge+` , Estimated Delivery: `+c.estimated_delivery_days+` days)</p>
                                 </li>
                                `
                    });
                    $('#courier_data').html(data);
                });

                /**
                 * Function to sort alphabetically an array of objects by some specific key.
                 *
                 * @param {String} property Key of the object to sort.
                 */
                function dynamicAlphabeticallySort(property) {
                    var sortOrder = 1;
                    if(property[0] === "-") {
                        sortOrder = -1;
                        property = property.substr(1);
                    }

                    return function (a,b) {
                        if(sortOrder == -1){
                            return b[property].localeCompare(a[property]);
                        }else{
                            return a[property].localeCompare(b[property]);
                        }
                    }
                }
            });
        })(jQuery);
    </script>

@endpush
