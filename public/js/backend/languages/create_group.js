$('#create-group').submit(function(){
    var formData = new FormData($("#create-group")[0]);
    $.ajax({
        url : url_create_group,
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
                var rowIndex = $('.table-groups').DataTable().row.add($.parseJSON(data).new_data).draw();
                var row = $('.table-groups').dataTable().fnGetNodes(rowIndex);
                $(row).attr('id', $.parseJSON(data).id);
                $("[data-toggle='tooltip']").tooltip();
                gen_numb('[id="group_numb"]', 1);
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