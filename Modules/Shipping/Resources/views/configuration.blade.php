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
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="main-title">
                                        <h3 class="mb-15">
                                            {{__('shipping.configuration')}}
                                        </h3>
                                        <hr class="dashed">
                                    </div>

                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'shipping.configuration.update',
                                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                    <div class="row">
                                        @if(auth()->user()->role->type =="superadmin")
                                        <div class="col-lg-3">
                                            <label class="primary_input_label" for="">{{__('shipping.order_confirm_and_sync_with_carrier')}} <span class="required_mark_theme">*</span></label>
                                            <ul class="permission_list sms_list">
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="order_confirm_and_sync" class="order_confirm_and_sync" type="radio" id="order_confirm_and_sync" value="Automatic" {{$row->order_confirm_and_sync == 'Automatic'? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.automatic')}}</p>
                                                </li>
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="order_confirm_and_sync" class="order_confirm_and_sync" type="radio" id="order_confirm_and_sync" value="Manual" {{$row->order_confirm_and_sync == 'Manual'? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.manual')}}</p>
                                                </li>
                                            </ul>
                                            <span class="text-danger">{{$errors->first('order_confirm_and_sync')}}</span>
                                        </div>
                                        @endif

                                        <div class="col-lg-3 auto_show_div">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="default_carrier">{{ __('shipping.carrier')}} <span class="required_mark_theme">*</span> </label>
                                                <select class="primary_select mb-15" id="default_carrier" name="default_carrier">
                                                    <option value="">{{__('common.select_one')}}</option>
                                                    @foreach($carriers as $carrier)
                                                        <option {{$carrier->id == $row->default_carrier ? 'selected' :''}} value="{{$carrier->id}}">{{$carrier->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

{{--                                        <div class="col-lg-3 auto_show_div">--}}
{{--                                            <label class="primary_input_label" for="">{{__('shipping.order_will_auto_confirm_?')}} <span class="required_mark_theme">*</span></label>--}}
{{--                                            <ul class="permission_list sms_list">--}}
{{--                                                <li>--}}
{{--                                                    <label class="primary_checkbox d-flex mr-12 ">--}}
{{--                                                        <input name="order_auto_confirm" class="order_auto_confirm" type="radio" id="order_auto_confirm" value="1" {{$row->order_auto_confirm == 1? 'Checked' :''}}>--}}
{{--                                                        <span class="checkmark"></span>--}}
{{--                                                    </label>--}}
{{--                                                    <p>{{__('shipping.yes')}}</p>--}}
{{--                                                </li>--}}
{{--                                                <li>--}}
{{--                                                    <label class="primary_checkbox d-flex mr-12 ">--}}
{{--                                                        <input name="order_auto_confirm" class="order_auto_confirm" type="radio" id="order_auto_confirm" value="0" {{$row->order_auto_confirm == 0 ? 'Checked' :''}}>--}}
{{--                                                        <span class="checkmark"></span>--}}
{{--                                                    </label>--}}
{{--                                                    <p>{{__('shipping.no')}}</p>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
{{--                                            <span class="text-danger">{{$errors->first('order_auto_confirm')}}</span>--}}
{{--                                        </div>--}}

                                        <div class="col-lg-3">
                                            <label class="primary_input_label" for="">{{__('shipping.customer_call_see_carrier_?')}} <span class="required_mark_theme">*</span></label>
                                            <ul class="permission_list sms_list">
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="carrier_show_for_customer" class="carrier_show_for_customer" type="radio" id="carrier_show_for_customer" value="1" {{$row->carrier_show_for_customer == 1? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.yes')}}</p>
                                                </li>
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="carrier_show_for_customer" class="carrier_show_for_customer" type="radio" id="carrier_show_for_customer" value="0" {{$row->carrier_show_for_customer == 0? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.no')}}</p>
                                                </li>
                                            </ul>
                                            <span class="text-danger">{{$errors->first('carrier_show_for_customer')}}</span>
                                        </div>

                                        <div class="col-lg-3">
                                            <label class="primary_input_label" for="">{{__('shipping.carrier_order_type')}}</label>
                                            <ul class="permission_list sms_list">
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="carrier_order_type" class="carrier_order_type" type="radio" id="carrier_order_type" value="Custom" {{$row->carrier_order_type == 'Custom'? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.Custom')}}</p>
                                                </li>
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="carrier_order_type" class="carrier_order_type" type="radio" id="carrier_order_type" value="Quick" {{$row->carrier_order_type == 'Quick'? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.Quick')}}</p>
                                                </li>
                                            </ul>
                                            <span class="text-danger">{{$errors->first('carrier_order_type')}}</span>
                                        </div>

                                        <div class="col-lg-3">
                                            <label class="primary_input_label" for="">{{__('shipping.refund_order_sync_carrier_?')}} <span class="required_mark_theme">*</span></label>
                                            <ul class="permission_list sms_list">
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="refund_order_sync_carrier" class="refund_order_sync_carrier" type="radio" id="refund_order_sync_carrier" value="1" {{$row->refund_order_sync_carrier == 1? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.yes')}}</p>
                                                </li>
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="refund_order_sync_carrier" class="refund_order_sync_carrier" type="radio" id="refund_order_sync_carrier" value="0" {{$row->refund_order_sync_carrier == 0? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.no')}}</p>
                                                </li>
                                            </ul>
                                            <span class="text-danger">{{$errors->first('refund_order_sync_carrier')}}</span>
                                        </div>

                                        <div class="col-lg-3">
                                            <label class="primary_input_label" for="">{{__('shipping.label_generate_use')}} <span class="required_mark_theme">*</span></label>
                                            <ul class="permission_list sms_list">
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="label_code" class="label_code" type="radio" id="label_code" value="barcode" {{$row->label_code == 'barcode'? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.barcode')}}</p>
                                                </li>
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="label_code" class="label_code" type="radio" id="label_code" value="qrcode" {{$row->label_code == 'qrcode'? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.qrcode')}}</p>
                                                </li>
                                                <li>
                                                    <label class="primary_checkbox d-flex mr-12 ">
                                                        <input name="label_code" class="label_code" type="radio" id="label_code" value="both" {{$row->label_code == 'both'? 'Checked' :''}}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{__('shipping.both')}}</p>
                                                </li>
                                            </ul>
                                            <span class="text-danger">{{$errors->first('label_code')}}</span>
                                        </div>



                                    @if(isModuleActive('ShipRocket') && auth()->user()->role->type =="superadmin")
                                            <div class="col-lg-3">
                                                <label class="primary_input_label" for="">{{__('shipping.seller_use_shiproket_?')}}</label>
                                                <ul class="permission_list sms_list">
                                                    <li>
                                                        <label class="primary_checkbox d-flex mr-12 ">
                                                            <input name="seller_use_shiproket" class="seller_use_shiproket" type="radio" id="seller_use_shiproket" value="1" {{$row->seller_use_shiproket == 1? 'Checked' :''}}>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{__('shipping.yes')}}</p>
                                                    </li>
                                                    <li>
                                                        <label class="primary_checkbox d-flex mr-12 ">
                                                            <input name="seller_use_shiproket" class="seller_use_shiproket" type="radio" id="seller_use_shiproket" value="0" {{$row->seller_use_shiproket == 0? 'Checked' :''}}>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <p>{{__('shipping.no')}}</p>
                                                    </li>
                                                </ul>
                                                <span class="text-danger">{{$errors->first('seller_use_shiproket')}}</span>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="row">

                                        <div class="col-xl-12 mt-repeater no-extra-space">
                                            <strong class="text-center">{{__('shipping.label_terms_and_conditions')}}</strong>
                                            @if(count($conditions) > 0)
                                                @foreach($conditions as $key =>$condition)
                                                    <div class="row">
                                                        <input type="hidden" name="conditionIds[{{$condition->id}}]" value="{{$condition->id}}">
                                                        <div class="col">
                                                            <div class="primary_input mb-25 position-relative">
                                                                <input value="{{$condition->condition}}" name="eCondition[{{$condition->id}}]" id="condition"  class="primary_input_field condition" placeholder="{{__('shipping.terms_and_conditions') }}" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <div class="position-relative form-group">
                                                                <a data-condition="{{$condition->id}}"  href="javascript:;" data-repeater-delete class="primary-btn condition_delete small icon-only fix-gr-bg  mt-repeater-delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div data-repeater-list="conditions">
                                                <div data-repeater-item class="mt-repeater-item">
                                                    <div class="mt-repeater-row">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="primary_input mb-25 position-relative">
                                                                    <input  name="condition" class="primary_input_field condition" placeholder="{{__('shipping.terms_and_conditions') }}" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <div class="position-relative form-group">
                                                                    <a  href="javascript:;" data-repeater-delete class="primary-btn small icon-only fix-gr-bg  mt-repeater-delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-offset-1 col-md-9">
                                                <a href="javascript:;" data-repeater-create  class="primary-btn radius_30px condition_edit  fix-gr-bg mt-repeater-add"><i class="fa fa-plus"></i>{{__('shipping.add_more')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" >
                                                <span class="ti-check"></span>
                                                {{__('common.update')}}
                                            </button>
                                        </div>
                                    </div>
                                    {{ Form::close() }}

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('Modules/Shipping/Resources/assets/js/repeater/repeater.js')}}"></script>
    <script src="{{asset('Modules/Shipping/Resources/assets/js/repeater/indicator-repeater.js')}}"></script>
   <script>
       (function ($) {
           "use strict";
           $(document).ready(function () {

               $(document).on('click','.condition_delete',function (event){
                   event.preventDefault();
                   let id = $(this).data('condition');
                   let url =  "{{route('shipping.label.terms_condition.destroy',':id')}}";
                   url = url.replace(':id',id);
                   let selectRow = $(this).parent().parent().parent();
                   $.ajax({
                       url: url,
                       type: "GET",
                       success: function(response) {
                           if(response.status == 200){
                               selectRow.remove();
                               toastr.success("Condition Deleted Successfully");
                           }
                       },
                       error: function(response) {
                           toastr.error("Something went wrong");
                       }
                   });
               });
               fieldToggleBasedOnOrderConfirm();

               $(document).on('change', '.order_confirm_and_sync', function(event){
                   fieldToggleBasedOnOrderConfirm();
               });

               function fieldToggleBasedOnOrderConfirm() {
                   let targetDiv = $('.auto_show_div');
                   let orderConfirm =  $('.order_confirm_and_sync:checked').val();
                   if(orderConfirm === 'Automatic'){
                       targetDiv.show();
                   }else {
                       targetDiv.hide();
                   }
               }

           });
       })(jQuery);
   </script>
@endpush

