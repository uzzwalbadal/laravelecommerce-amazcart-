@push('scripts')
    <script type="text/javascript">

        (function($){
            "use strict";

            $(document).ready(function () {

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

                $(document).on('submit', '#createForm', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    resetValidationError();
                    let formElement = $(this).serializeArray()
                    let formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name,element.value);
                    });
                    formData.append('_token',"{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('shipping.pickup_locations.store')}}",
                        type:"POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success:function(response){
                            resetAfterChange(response.TableData);
                            create_form_reset();
                            toastr.success("{{__('common.created_successfully')}}", "{{__('common.success')}}");
                            let res = response.ship_rocket_response;
                            if (res.length > 0) {
                                toastr.success(res);
                            }
                                $('#pre-loader').addClass('d-none');
                        },
                        error:function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            showValidationErrors('#createForm',response.responseJSON.errors);
                            $('#pre-loader').addClass('d-none');
                        }
                    });
                });

                $(document).on("click", ".view_row", function (event) {
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    let id = $(this).data("id");
                    let url =  "{{route('shipping.pickup_locations.show',':id')}}";
                    url = url.replace(':id',id);
                    $.get(url, function(data){
                        $('#append_html').html(data);
                        $('#pre-loader').addClass('d-none');
                        $('#view_modal').modal('show');
                    });

                });

                $(document).on("click", ".edit_row", function (event) {
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    let id = $(this).data("id");
                    let url =  "{{route('shipping.pickup_locations.edit',':id')}}";
                    url = url.replace(':id',id);
                    $.get(url, function(data){
                        $('.create_div').html(data);
                        $('#pre-loader').addClass('d-none');
                        $(".primary_select").niceSelect();
                    });

                });

                $(document).on('submit', '#editForm', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    resetValidationError();
                    let formElement = $(this).serializeArray()
                    let formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name,element.value);
                    });
                    formData.append('_token',"{{ csrf_token() }}");
                    resetValidationError();
                    let id = $('#rowId').val();
                    let url =  "{{route('shipping.pickup_locations.update',':id')}}";
                    url = url.replace(':id',id);
                    $.ajax({
                        url: url,
                        type:"POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success:function(response){
                            resetAfterChange(response.TableData);
                            toastr.success('{{__("common.updated_successfully")}}',"{{__('common.success')}}");
                            $('#pre-loader').addClass('d-none');
                            $('.create_div').html(response.createForm);
                            $(".primary_select").niceSelect();

                        },
                        error:function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            showValidationErrors('#editForm',response.responseJSON.errors);
                            $('#pre-loader').addClass('d-none');
                        }
                    });
                });

                $(document).on("click", ".delete_row", function (event) {
                    event.preventDefault();
                    let id = $(this).data("id");
                    $('#pickup_location_delete_id').val(id);
                    $('#pickup_location_delete_modal').modal('show');

                });

                $(document).on('submit', '#pickup_location_delete_form', function(event) {
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#pickup_location_delete_modal').modal('hide');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', $('#pickup_location_delete_id').val());
                    $.ajax({
                        url: "{{ route('shipping.pickup_locations.destroy') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            if(response.msg){
                                toastr.warning(response.msg);
                                $("#pre-loader").addClass('d-none');
                            }else{
                                resetAfterChange(response.TableData);
                                toastr.success("{{__('common.deleted_successfully')}}","{{__('common.success')}}")
                                $('#pre-loader').addClass('d-none');
                                $('.create_div').html(response.createForm);
                                $(".primary_select").niceSelect();
                            }

                        },
                        error: function(response) {

                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.error_message')}}'","{{__('common.error')}}");
                        }
                    });
                });

                $(document).on('change', '.status_change', function(event){
                    event.preventDefault();
                    let status = 0;
                    if($(this).prop('checked')){
                        status = 1;
                    }
                    else{
                        status = 0;
                    }
                    let id = $(this).data('id');
                    $('#pre-loader').removeClass('d-none');
                    let formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', id);
                    formData.append('status', status);

                    $.ajax({
                        url: "{{ route('shipping.pickup_locations.status') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {

                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                    });

                });

                $(document).on('change', '.set_default', function(event){
                    event.preventDefault();
                    let set_default = 0;
                    if($(this).prop('checked')){
                        set_default = 1;
                    }
                    else{
                        set_default = 0;
                    }
                    let id = $(this).data('id');
                    $('#pre-loader').removeClass('d-none');
                    let formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', id);
                    formData.append('set_default', set_default);

                    $.ajax({
                        url: "{{ route('shipping.pickup_locations.set_default') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            resetAfterChange(response.TableData);
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {

                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                    });

                });



                function create_form_reset(){
                    $('#createForm')[0].reset();
                    $('.primary_select').niceSelect('update');
                }

                function resetAfterChange(response){
                    $('#item_list').html(response);
                    CRMTableThreeReactive();
                }

                function showValidationErrors(formType, errors){
                    $(formType +' #error_pickup_location').text(errors.pickup_location);
                    $(formType +' #error_name').text(errors.name);
                    $(formType +' #error_email').text(errors.email);
                    $(formType +' #error_phone').text(errors.phone);
                    $(formType +' #error_address').text(errors.address);
                    $(formType +' #error_address_2').text(errors.address_2);
                    $(formType +' #error_pin_code').text(errors.pin_code);
                    $(formType +' #error_country_id').text(errors.country_id);
                    $(formType +' #error_state_id').text(errors.state_id);
                    $(formType +' #error_city_id').text(errors.city_id);
                }

                function resetValidationError(){
                    $('#error_pickup_location').text('');
                    $('#error_name').text('');
                    $('#error_email').text('');
                    $('#error_phone').text('');
                    $('#error_address').text('');
                    $('#error_address_2').text('');
                    $('#error_pin_code').text('');
                    $('#error_country_id').text('');
                    $('#error_state_id').text('');
                    $('#error_city_id').text('');
                }
            });
        })(jQuery);

    </script>
@endpush
