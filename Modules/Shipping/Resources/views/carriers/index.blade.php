@extends('backEnd.master')
@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('modules/paymentgateway/css/style.css'))}}" />
    <style>
        #logo_preview{
            width: 80px;
            height: 70px;
        }
    </style>
@endsection
@section('mainContent')
    <div class="row">
        @php
            $activeAutomaticCarrier = $carriers->where('type','Automatic')->where('status',1)->count();
        @endphp
        <div class="{{$activeAutomaticCarrier > 0 ? 'col-md-5 col-sm-6 col-xs-12': "col-md-12 col-sm-12 col-xs-12"}}">
            <div class="main-title mb-25 d-md-flex">
                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('shipping.carriers') }}</h3>
                <ul class="d-flex">
                    @if(permissionCheck('shipping.carriers.store'))

                    @endif
                    <li>
                        <a  data-toggle="modal" data-target="#add_carrier_modal" class="primary-btn radius_30px mr-10 fix-gr-bg" href="#">
                            <i class="ti-plus"></i> {{ __('common.add_new') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="common_QA_section QA_section_heading_custom">
                <div class="QA_table ">
                    <!-- table-responsive -->
                    <div id="carrier_list">
                        @include('shipping::carriers.list')
                    </div>
                </div>
            </div>
        </div>

        @if($activeAutomaticCarrier > 0)

        <div class="col-md-7 col-sm-6 col-xs-12">
            <section class="admin-visitor-area up_st_admin_visitor">
                <div class="container-fluid p-0">
                    <div class="row config_list" id="form_list_div">
                        @include('shipping::carriers.components._config', [$carriers])
                    </div>
                </div>
            </section>
        </div>
        @endif

    </div>
    <div id="append_html"></div>
    @include('shipping::carriers.create')
    @include('backEnd.partials._deleteModalForAjax',['item_name' => __('shipping.carrier'),'form_id' =>
'carrier_delete_form','modal_id' => 'carrier_delete_modal', 'delete_item_id' => 'carrier_delete_id'])
    <input type="hidden" value="{{route('shipping.carriers.store')}}" id="carrier_store_url">
    <input type="hidden" value="{{route('shipping.carriers.edit',':id')}}" id="carrier_edit_url">
    <input type="hidden" value="{{route('shipping.carriers.update',':id')}}" id="carrier_update_url">
    <input type="hidden" value="{{route('shipping.carriers.destroy')}}" id="carrier_delete_url">
@endsection
@push('scripts')
    <script src="{{asset('Modules/Shipping/Resources/assets/js/carrier.js')}}"></script>
    <script type="text/javascript">
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('click','.disable_shiprocket',function (){
                    toastr.info('Please Configure Shiprocket First');
                })


                $(document).on('change','.carrier_activate', function(){
                    let carrier_id = $(this).data('carrier');
                    if(this.checked){
                        var status = 1;
                    }
                    else{
                        var status = 0;
                    }
                    $('#pre-loader').removeClass('d-none');
                    $.post('{{ route('shipping.carriers.status') }}', {_token:'{{ csrf_token() }}', id:this.value, status:status,carrier_id:carrier_id }, function(data){
                        if(data.status == 1){
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#form_list_div').html(data.list);
                            $('#pre-loader').addClass('d-none');
                        }
                        else{
                            toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                        location.reload(true);
                    }).fail(function(response) {
                        if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            location.reload(true);
                            return false;
                        }

                    });
                });



                $(document).on('change', '#shiprocket_logo', function(){
                    getFileName($(this).val(),'#shiprocket_image_file');
                    imageChangeWithFile($(this)[0],'#ShiprocketImgDiv');
                });

            });

        })(jQuery);
    </script>
@endpush
