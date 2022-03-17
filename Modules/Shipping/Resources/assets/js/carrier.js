(function($) {
    "use strict";
    let _token = $('meta[name=_token]').attr('content') ;
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();

        $(document).on('change', '#logo', function(event){
            getFileName($(this).val(),'#logo_name');
            imageChangeWithFile($(this)[0],'#logo_preview');
        });

        $(document).on('submit', '#create_form', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            let logo = $('#logo')[0].files[0];

            if(logo){
                formData.append('logo',logo);
            }
            formData.append('_token',_token);
            let url = $('#carrier_store_url').val();
            resetValidationError();

            $.ajax({
                url: url,
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    resetAfterChange(response.carrier_list,response.config);
                    create_form_reset();
                    $('#add_carrier_modal').modal('hide');
                    toastr.success("Carrier Add Successfully");
                    $('#pre-loader').addClass('d-none');

                },
                error:function(response) {
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,"Error");
                        $('#pre-loader').addClass('d-none');
                        return false;
                    }
                    showValidationErrors('#create_form',response.responseJSON.errors);
                    $('#pre-loader').addClass('d-none');
                }
            });
        });

        $(document).on('click', '.edit_carrier', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let id = $(this).data('id');
            let url =  $('#carrier_edit_url').val();
            url = url.replace(':id',id);
            $.get(url, function(response){
                if(response.msg_type == 'Manual'){
                    $('#append_html').html(response.view);
                    $('#edit_carrier_modal').modal('show');
                    $('[data-toggle="tooltip"]').tooltip();
                }else{
                    toastr.error('Automatic Carriers Is Not Editable.');
                }
                $('#pre-loader').addClass('d-none');
            });
        });

        $(document).on('submit', '#update_form', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            let logo = $('#logo')[0].files[0];

            if(logo){
                formData.append('logo',logo);
            }
            formData.append('_token',_token);

            let id = $('#rowId').val();
            let url = $('#carrier_update_url').val();
            url = url.replace(':id',id);
            resetValidationError();
            $.ajax({
                url: url,
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    resetAfterChange(response.carrier_list,response.config);
                    $('#edit_carrier_modal').modal('hide');
                    $('#pre-loader').addClass('d-none');
                    toastr.success("Carrier Update Successfully");
                },
                error:function(response) {
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,"Error");
                        $('#pre-loader').addClass('d-none');
                        return false;
                    }
                    showValidationErrors('#update_form',response.responseJSON.errors);
                    $('#pre-loader').addClass('d-none');
                }
            });
        });

        $(document).on("click", ".delete_carrier", function (event) {
            event.preventDefault();
            let id = $(this).data("id");
            $('#carrier_delete_id').val(id);
            $('#carrier_delete_modal').modal('show');

        });

        $(document).on('submit', '#carrier_delete_form', function(event) {
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            $('#carrier_delete_modal').modal('hide');
            var formData = new FormData();
            formData.append('_token', _token);
            formData.append('id', $('#carrier_delete_id').val());
            let url = $('#carrier_delete_url').val();
            $.ajax({
                url: url,
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    if(response.msg_type == 'Automatic'){
                        toastr.error('Automatic carrirers delete not possible.');
                    }
                    else if(response.msg_type == 'last_item'){
                        toastr.error('Last carrirer delete not possible.');
                    }
                    else if(response.msg_type == 'has_shipping_method'){
                        toastr.error('This carrier added on Shipping Rate.');
                    }
                    else{
                        toastr.success("Deleted Successfully","Success")
                    }
                    resetAfterChange(response.carrier_list,response.config);
                    $("#pre-loader").addClass('d-none');

                },
                error: function(response) {

                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,"Error");
                        $('#pre-loader').addClass('d-none');
                        return false;
                    }
                    toastr.error("Error Message","Error");
                }
            });
        });

        function resetAfterChange(list,config){
            $('#carrier_list').html(list);
            $('.config_list').html(config);
            CRMTableTwoReactive();
        }

        function create_form_reset(){
            $('#create_form')[0].reset();
        }


        function showValidationErrors(formType, errors){
            $(formType +' #error_name').text(errors.name);
            $(formType +' #error_tracking_url').text(errors.tracking_url);
            $(formType +' #error_logo').text(errors.logo);
        }


        function resetValidationError(){
            $('#error_name').html('');
            $('#error_tracking_url').html('');
            $('#error_logo').html('');
        }

    });
})(jQuery);
