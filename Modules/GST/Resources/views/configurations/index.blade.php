@extends('backEnd.master')
@section('mainContent')

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0 mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="white_box_50px box_shadow_white">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('gst.gst_configuration') }} /TAX/VAT</h3>
                        </div>
                    </div>
                    <form action="{{route("gst_tax.configuration_update")}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="permission_list sms_list">
                                    <li>
                                        <label class="primary_checkbox d-flex mr-12 ">
                                            <input name="enable_gst" type="radio" id="enable_gst_1" value="gst" @if (app('gst_config')['enable_gst'] == "gst") checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <p>{{ __('gst.is_active_gst') }}</p>
                                    </li>
                                    <li>
                                        <label class="primary_checkbox d-flex mr-12 ">
                                            <input name="enable_gst" type="radio" id="enable_gst_2" value="flat_tax" @if (app('gst_config')['enable_gst'] == "flat_tax") checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <p>{{ __('gst.is_active_flat_tax') }}</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-12 inside_state_div @if (app('gst_config')['enable_gst'] != "gst") d-none @endif">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">{{ __('gst.place_of_delivery_inside_state') }}</label>
                                    <select class="primary_select mb-25" name="within_a_single_state[]" id="within_a_single_state" multiple>
                                        <option value="0" disabled>{{ __('gst.select_one_or_multiple') }}</option>
                                        @foreach ($gst_lists as $key => $gst)
                                            <option value="{{ $gst->id }}" @if (in_array ($gst->id, app('gst_config')['within_a_single_state'])) selected @endif>{{ $gst->name }} ({{ $gst->tax_percentage }} %)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 outside_state_div @if (app('gst_config')['enable_gst'] != "gst") d-none @endif">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">{{ __('gst.place_of_delivery_outside_state') }}</label>
                                    <select class="primary_select mb-25" name="between_two_different_states_or_a_state_and_a_Union_Territory[]" id="between_two_different_states_or_a_state_and_a_Union_Territory" multiple>
                                        <option value="0" disabled>{{ __('gst.select_one_or_multiple') }}</option>
                                        @foreach ($gst_lists as $key => $gst)
                                            <option value="{{ $gst->id }}" @if (in_array ($gst->id, app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])) selected @endif>{{ $gst->name }} ({{ $gst->tax_percentage }} %)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 flat_div @if (app('gst_config')['enable_gst'] == "gst") d-none @endif">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">{{ __('gst.flat_tax_percentage') }}</label>
                                    <select class="primary_select mb-25" name="flat_tax_id" id="flat_tax_id">
                                        <option value="0" disabled>{{ __('gst.select_one_or_multiple') }}</option>
                                        @foreach ($gst_lists as $key => $gst)
                                            <option value="{{ $gst->id }}" @if (app('gst_config')['flat_tax_id'] == $gst->id) selected @endif>{{ $gst->name }} ({{ $gst->tax_percentage }} %)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if (permissionCheck('gst_tax.configuration_update'))
                                <div class="col-12">
                                    <div class="submit_btn text-center ">
                                        <button class="primary-btn semi_large2 fix-gr-bg"><i class="ti-check"></i> {{ __('common.update') }} </button>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12 text-center mt-2">
                                    <span class="alert alert-warning" role="alert">
                                        <strong>
                                            {{ __('common.you_don_t_have_this_permission') }}
                                        </strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12 mt-60">
                <section class="admin-visitor-area up_st_admin_visitor">
                    @include('backEnd.partials._deleteModalForAjax',['item_name' => __("Group")])
                    <div class="container-fluid p-0">
                        <div class="row justify-content-center">
                            
                            <div class="col-lg-4">
                                <div class="row">
                                    <div id="formHtml" class="col-lg-12">
                                        @include('gst::configurations.components.add_group')
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-8 list_div">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-md-flex">
                                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('Group List')}}</h3>
                                    </div>
                                </div>
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table ">
                                        <!-- table-responsive -->
                                        <div class="">
                                            <div id="item_table">
                                                @include('gst::configurations.components.group_list')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

@endsection
@push('scripts')
    <script type="text/javascript">
        (function($) {
        	"use strict";
            $(document).ready(function(){
                $(document).on('change', 'input[type=radio][name=enable_gst]', function(){
                    if (this.value == 'gst') {
                        $(".flat_div").addClass('d-none');
                        $(".outside_state_div").removeClass('d-none');
                        $(".inside_state_div").removeClass('d-none');
                    }
                    else if (this.value == 'flat_tax') {
                        $(".flat_div").removeClass('d-none');
                        $(".outside_state_div").addClass('d-none');
                        $(".inside_state_div").addClass('d-none');
                    }
                });

                $(document).on('change', '#outsite_state_gst', function(event){
                    let list = $(this).val();
                    $('#outsite_gst_list_div').html('');
                    $('#pre-loader').removeClass('d-none');
                    if(list.length){
                        let data = {
                            _token:'{{csrf_token()}}',
                            lists : list
                        }
                        $.post("{{route('gst_tax.get_outsite_state_gst')}}", data, function(response){
                            if(response){
                                $('#outsite_gst_list_div').html(response);
                            }
                            $('#pre-loader').addClass('d-none');
                        });
                    }else{
                        $('#pre-loader').addClass('d-none');
                        $('#outsite_state_gst').niceSelect('update');
                        toastr.info('Atleast one need to select.');
                    }
                });

                $(document).on('change', '#outsite_state_gst_edit', function(event){
                    let list = $(this).val();
                    $('#outsite_gst_list_div').html('');
                    $('#pre-loader').removeClass('d-none');
                    let prev_val = $('#prev_outsite_state').val();
                    if(list.length){
                        let data = {
                            _token:'{{csrf_token()}}',
                            lists : list,
                            prev_val : prev_val
                        }
                        $.post("{{route('gst_tax.get_outsite_state_gst_edit')}}", data, function(response){
                            if(response){
                                $('#outsite_gst_list_div').html(response);
                            }
                            $('#pre-loader').addClass('d-none');
                        });
                    }else{
                        $('#pre-loader').addClass('d-none');
                        $('#outsite_state_gst').niceSelect('update');
                        toastr.info('Atleast one need to select.');
                    }
                });

                $(document).on('change', '#same_state_gist', function(event){
                    let list = $(this).val();
                    $('#same_state_gst_list_div').html('');
                    $('#pre-loader').removeClass('d-none');
                    if(list.length){
                        let data = {
                            _token:'{{csrf_token()}}',
                            lists : list
                        }
                        $.post("{{route('gst_tax.get_same_state_gst')}}", data, function(response){
                            if(response){
                                $('#same_state_gst_list_div').html(response);
                            }
                            $('#pre-loader').addClass('d-none');
                        });
                    }else{
                        $('#pre-loader').addClass('d-none');
                        $('#same_state_gist').niceSelect('update');
                        toastr.info('Atleast one need to select.');
                    }
                });
                $(document).on('change', '#same_state_gist_edit', function(event){
                    let list = $(this).val();
                    $('#same_state_gst_list_div').html('');
                    $('#pre-loader').removeClass('d-none');
                    let prev_val = $('#prev_same_state').val();
                    if(list.length){
                        let data = {
                            _token:'{{csrf_token()}}',
                            lists : list,
                            prev_val : prev_val
                        }
                        $.post("{{route('gst_tax.get_same_state_gst_edit')}}", data, function(response){
                            if(response){
                                $('#same_state_gst_list_div').html(response);
                            }
                            $('#pre-loader').addClass('d-none');
                        });
                    }else{
                        $('#pre-loader').addClass('d-none');
                        $('#same_state_gist').niceSelect('update');
                        toastr.info('Atleast one need to select.');
                    }
                });

                $(document).on('submit', '#add_group_form', function(event){
                    event.preventDefault();
                    $("#pre-loader").removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('gst_tax.store_group') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            resetAfterChange(response);
                            toastr.success("{{__('common.created_successfully')}}", "{{__('common.success')}}");
                            $("#pre-loader").addClass('d-none');
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            showValidationErrors(response.responseJSON.errors);
                            $("#create_btn").prop('disabled', false);
                            $('#create_btn').text('{{ __("common.save") }}');
                            $('#parent_id').niceSelect();
                            $("#pre-loader").addClass('d-none');
                        }
                    });
                });

                $(document).on('submit', '#edit_group_form', function(event){
                    event.preventDefault();
                    $("#pre-loader").removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('gst_tax.update_group') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            resetAfterChange(response);
                            toastr.success("{{__('common.created_successfully')}}", "{{__('common.success')}}");
                            $("#pre-loader").addClass('d-none');
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            showValidationErrors(response.responseJSON.errors);
                            $("#create_btn").prop('disabled', false);
                            $('#create_btn').text('{{ __("common.save") }}');
                            $('#parent_id').niceSelect();
                            $("#pre-loader").addClass('d-none');
                        }
                    });
                });

                $(document).on('click', '.delete_group', function(event){
                    event.preventDefault();
                    let id = $(this).data('id');
                    $('#delete_item_id').val(id);
                    $('#deleteItemModal').modal('show');
                });

                $(document).on('submit', '#item_delete_form', function(event) {
                    event.preventDefault();
                    $('#deleteItemModal').modal('hide');
                    $("#pre-loader").removeClass('d-none');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', $('#delete_item_id').val());
                    let id = $('#delete_item_id').val();
                    $.ajax({
                        url: "{{ route('gst_tax.delete_group') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            if(response.parent_msg){
                                toastr.warning(response.parent_msg);
                                $("#pre-loader").addClass('d-none');
                            }
                            else{
                                resetAfterChange(response);
                                toastr.success("{{__('common.deleted_successfully')}}", "{{__('common.success')}}");
                                $("#pre-loader").addClass('d-none');

                            }

                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            // toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            $("#pre-loader").addClass('d-none');
                        }
                    });
                });

                $(document).on('click', '.edit_group', function(event){
                    event.preventDefault();
                    let id = $(this).data('id');

                    $("#pre-loader").removeClass('d-none');
                    let base_url = $('#url').val();
                    let url = base_url + '/gst-setup/gst/group/' + id + '/edit'
                    $.ajax({
                        url: url,
                        type: "GET",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#formHtml').html(response);
                            $("#pre-loader").addClass('d-none');
                            $('select').niceSelect();
                        },
                        error: function(response) {
                            toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            $("#pre-loader").addClass('d-none');
                        }
                    });
                });

                function resetAfterChange(response) {
                    $('#item_table').html(response.list);
                    $('#formHtml').html(response.createForm);
                    CRMTableThreeReactive();
                    $('select').niceSelect();
                }

                function showValidationErrors(errors) {
                    $('#error_name').text(errors.name);
                    $('#error_same_state_gst').text(errors.same_state_gst);
                    $('#error_outsite_state_gst').text(errors.outsite_state_gst);
                }

                function resetValidationErrors(){
                    $('#error_name').text('');
                    $('#error_slug').text('');
                    $('#error_searchable').text('');
                    $('#error_icon').text('');
                    $('#error_status').text('');
                    $('#error_image').text('');
                }
            });
        })(jQuery);
    </script>
@endpush
