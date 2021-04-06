$('[name="timezone"]').select2({
    placeholder: select_timezone
});
$('[name="module"]').select2({
    placeholder: select_module
});
$('[name="records[]"]').select2({
    placeholder : select_records,
});

$(document).ready(function(){
    var module_val = $('[name="module"]').val();
    if (module_val != '') {
        get_records($('[name="module"]'));
    }
});

function get_records(obj)
{
    var id_module = $(obj).val();

    $.ajax({
        url : url_get_records,
        data : ({id_module : id_module, _token : my_token}),
        type : 'post',
        dataType : 'json',
        beforeSend : function(){
            $('[name="records[]"]').prop('disabled', true);
            $('input[type="submit"]').prop('disabled', true);
            $(".ajax_loader").show();
        },
        success : function(msg){
            $('[name="records[]"]').select2('data', null);
            $('[name="records[]"] option').remove();

            if(msg['records'].length > 0){
                $.each(msg['records'], function(i,n){    
                    $('[name="records[]"]').append("<option value='"+n['id_record']+"'>"+n['name']+"</option>");    
                });
            }
            $('[name="records[]"]').select2('val', except_records);
        },
        complete : function(){
            $('[name="records[]"]').prop('disabled', false);
            $('input[type="submit"]').prop('disabled', false);
            $(".ajax_loader").hide();
        }
    });
}