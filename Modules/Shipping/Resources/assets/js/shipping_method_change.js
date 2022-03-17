(function($) {
    "use strict";
    let _token = $('meta[name=_token]').attr('content') ;
    $(document).ready(function(){

        var totalOrder = $('#total_order').val();

        $(document).on('change', '#checked_all', function(event){
            if($(this).is(':checked',true))
            {
                $(".order_id").prop('checked', true);
            } else {
                $(".order_id").prop('checked',false);
            }
        });


        $(document).on('change', '.order_id', function(event){
            if(totalOrder === $(this).is(':checked').length)
            {
                $("#checked_all").prop('checked', true);
            } else {
                $("#checked_all").prop('checked',false);
            }
        });


        $(document).on('click', '.change_shipping_method', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let id = $(this).data('id');
            let url =  $('#shipping_method_change_url').val();
            url = url.replace(':id',id);
            $.get(url, function(response){
                if(response){
                    $('#append_html').html(response);
                    $('.primary_select').niceSelect();
                    $('#single_order_method_change_modal').modal('show');
                    $('#pre-loader').addClass('d-none');
                }
            });
        });

        $(document).on('submit', '#shipping_method_change', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            formData.append('_token',_token);
            let url = $('#shipping_method_update_url').val();
            resetValidationError();
            $.ajax({
                url: url,
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    create_form_reset();
                    $('#single_order_method_change_modal').modal('hide');
                    $('#pre-loader').addClass('d-none');
                    location.reload();
                    toastr.success('Shipping Method Change  Successfully');
                },
                error:function(response) {
                    $('#pre-loader').addClass('d-none');
                    showValidationErrors('#shipping_method_change',response.responseJSON.errors);
                }
            });
        });

        $(document).on('click', '#shipping_method_changes', function(event){
            var orders = [];
            $('#pre-loader').removeClass('d-none');
            $(".order_id:checked").each(function() {
                orders.push($(this).attr('data-id'));
            });
            if(orders.length <=0)
            {
                $('#pre-loader').addClass('d-none');
                toastr.warning("Please select order.");
            }  else {
                $(".modal-body #orderIds").val(JSON.stringify(orders));
                $('#pre-loader').addClass('d-none');
                $('#multiple_order_method_change_modal').modal('show');
            }
        });


        $(document).on('submit', '#multiple_shipping_method_change', function(event){
            event.preventDefault();
            $('#pre-loader').removeClass('d-none');
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            formData.append('_token',_token);
            let url = $('#shipping_method_update_url').val();
            resetValidationError();
            $.ajax({
                url: url,
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    create_form_reset2();
                    $('#multiple_order_method_change_modal').modal('hide');
                    $('#pre-loader').addClass('d-none');
                    location.reload();
                    toastr.success('Users To Employee Convert Successfully');
                },
                error:function(response) {
                    $('#pre-loader').addClass('d-none');
                    showValidationErrors('#multiple_shipping_method_change',response.responseJSON.errors);
                }
            });
        });

        function create_form_reset2(){
            $(".primary_select").niceSelect('update');
            $('#multiple_shipping_method_change')[0].reset();
        }
        function create_form_reset(){
            $(".primary_select").niceSelect('update');
            $('#shipping_method_change')[0].reset();
        }
        function showValidationErrors(formType, errors){
            $(formType +' #error_shipping_method').text(errors.shipping_method);
        }
        function resetValidationError(){
            $('#error_shipping_method').html('');
        }
    });
})(jQuery);
