$('#edit-group').submit(function(){
    var formData = new FormData($("#edit-group")[0]);
    $.ajax({
        url : url_edit_group,
        type : 'post',
        data : formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $('input[type="submit"]').prop('disabled', true);
            $(".ajax_loader").show();
        },
        success : function(data) {
            if ($.parseJSON(data).type == 'success') {
                $('#myModal').modal('hide');
                $('#record_code'+id_language).html($.parseJSON(data).code);
                $('#record_lang'+id_language).html($.parseJSON(data).lang);
                $('#record_name'+id_language).html($.parseJSON(data).name);
                $('#record_flag'+id_language).html('<img src="'+$.parseJSON(data).flag+'" style="width: 20px; height: 20px;">');
                if (type == 'old') {
                    $('.table-groups').DataTable().rows().invalidate().draw();
                }
            }
            Command: toastr[$.parseJSON(data).type]($.parseJSON(data).text, $.parseJSON(data).title);
        },
        complete: function() {
            $('input[type="submit"]').prop('disabled', false);
            $(".ajax_loader").hide();
        }
    });
    return false;
});