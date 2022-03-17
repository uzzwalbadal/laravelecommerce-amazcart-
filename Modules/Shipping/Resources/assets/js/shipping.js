(function($) {
    "use strict";
    let _token = $('meta[name=_token]').attr('content') ;
    $(document).ready(function(){

        $(document).on('click', '.carrier_status', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let id = $(this).data('id');
            let url =  $('#carrier_status_url').val();
            url = url.replace(':id',id);
            $.get(url, function(response){
                if(response){
                    $('#append_html').html(response);
                    $('#carrier_status_modal').modal('show');
                    $('#pre-loader').addClass('d-none');
                }
            });
        });

        $(document).on('click', '.customer_address_edit', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let id = $(this).data('id');
            let url =  $('#customer_address_edit').val();
            url = url.replace(':id',id);
            $.get(url, function(response){
                if(response){
                    $('#append_html').html(response);
                    $('#customer_address_edit_modal').modal('show');
                    $('.primary_select').niceSelect();
                    $('#pre-loader').addClass('d-none');
                }
            });
        });

        $(document).on('submit', '#address_form', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            formData.append('_token',_token);
            let url = $('#customer_address_update').val();
            resetValidationError();
            $.ajax({
                url: url,
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    $('#customer_address_edit_modal').modal('hide');
                    $('#pre-loader').addClass('d-none');
                    location.reload();
                    toastr.success('Shipping Address Updated Successfully');
                },
                error:function(response) {
                    $('#pre-loader').addClass('d-none');
                    showValidationErrors('#address_form',response.responseJSON.errors);
                }
            });
        });


        $(document).on('click', '.packaging_edit', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let id = $(this).data('id');
            let url =  $('#packaging_edit_url').val();
            url = url.replace(':id',id);
            $.get(url, function(response){
                if(response){
                    $('#append_html').html(response);
                    $('#packaging_modal').modal('show');
                    $('#pre-loader').addClass('d-none');
                }
            });
        });

        $(document).on('submit', '#packaging_form', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            formData.append('_token',_token);
            let url = $('#packaging_update_url').val();
            resetValidationError();
            $.ajax({
                url: url,
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    $('#packaging_modal').modal('hide');
                    $('#pre-loader').addClass('d-none');
                    location.reload();
                    toastr.success('Packaging Info Updated Successfully');
                },
                error:function(response) {
                    $('#pre-loader').addClass('d-none');
                    showValidationErrors('#packaging_form',response.responseJSON.errors);
                }
            });
        });


        function showValidationErrors(formType, errors){
            $(formType +' #error_weight').text(errors.weight);
            $(formType +' #error_length').text(errors.length);
            $(formType +' #error_breadth').text(errors.breadth);
            $(formType +' #error_height').text(errors.height);
            $(formType +' #error_customer_shipping_name').text(errors.customer_shipping_name);
            $(formType +' #error_customer_shipping_email').text(errors.customer_shipping_email);
            $(formType +' #error_customer_shipping_phone').text(errors.customer_shipping_phone);
            $(formType +' #error_customer_shipping_address').text(errors.customer_shipping_address);
            $(formType +' #error_customer_shipping_post_code').text(errors.customer_shipping_post_code);
            $(formType +' #error_customer_shipping_country').text(errors.customer_shipping_country);
            $(formType +' #error_customer_shipping_state').text(errors.customer_shipping_state);
            $(formType +' #error_customer_shipping_city').text(errors.customer_shipping_city);

            $(formType +' #error_customer_billing_name').text(errors.customer_billing_name);
            $(formType +' #error_customer_billing_email').text(errors.eustomer_billing_email);
            $(formType +' #error_customer_billing_phone').text(errors.customer_billing_phone);
            $(formType +' #error_customer_billing_address').text(errors.customer_billing_address);
            $(formType +' #error_customer_billing_post_code').text(errors.customer_billing_post_code);
            $(formType +' #error_customer_billing_country').text(errors.customer_billing_country);
            $(formType +' #error_customer_billing_state').text(errors.customer_billing_state);
            $(formType +' #error_customer_billing_city').text(errors.customer_billing_city);
        }
        function resetValidationError(){
            $('#error_weight').html('');
            $('#error_length').html('');
            $('#error_breadth').html('');
            $('#error_height').html('');
            $('#error_customer_shipping_name').html('');
            $('#error_customer_shipping_email').html('');
            $('#error_customer_shipping_phone').html('');
            $('#error_customer_shipping_address').html('');
            $('#error_customer_shipping_post_code').html('');
            $('#error_customer_shipping_country').html('');
            $('#error_customer_shipping_state').html('');
            $('#error_customer_shipping_city').html('');
            $('#error_customer_billing_name').html('');
            $('#error_customer_billing_email').html('');
            $('#error_customer_billing_phone').html('');
            $('#error_customer_billing_address').html('');
            $('#error_customer_billing_post_code').html('');
            $('#error_customer_billing_country').html('');
            $('#error_customer_billing_state').html('');
            $('#error_customer_billing_city').html('');
        }
    });
})(jQuery);
