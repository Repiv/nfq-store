"use strict";

var $form = $('#order-form');

$(function() {
    $('#products .product').click(function() {
        $(this).toggleClass('active');
        
        if($('#products .active').length) {
            $('#order-form-wrap').addClass('active');
            
            var total = 0;
            
            $('#products .active').each(function() {
                total += parseFloat($(this).find('.price').text());
            });
            
            $('#order-form-total').text(total.toFixed(2) + '€')
        } else {
            $('#order-form-wrap').removeClass('active');
        }
    });
    
    $('#order-form-submit').click(function() {
        $('#appbundle_order_products option:selected').removeAttr('selected');
        
        $('#products .active').each(function() {
            $('#appbundle_order_products option[value="' + $(this).data('id') + '"]').attr('selected', 'selected');
        });
    });
    
    if($form.length) {
        $form.validate({
            submitHandler: function() {
                $.ajax({
                    method: 'POST',
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    beforeSend: function() {
                        $form.find('span.error').remove();
                        $('#products').find('.alert').remove();
                    },
                    success: function(data) {
                        $('#products .active').removeClass('active');

                        $('#products').before('<div class="alert alert-success">' + data + '</div>');
                        
                        $('#orderModal').modal('hide');
                        $('#order-form-wrap').removeClass('active');
                    },
                    error: function(data) {
                        var errors = $.parseJSON(data.responseText);

                        for (var key in errors) {
                            $form.find('#appbundle_order_' + key).after('<span class="error">' + errors[key][0] + '</span>');
                        }

                    }
                });

                return false;
            }
        });
    }
});
