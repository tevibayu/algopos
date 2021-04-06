function detail(id, orderid)
{
    
    $('#seeDetail .modal-title').text('Order Detail : #' + orderid);

    $.ajax({
        type: "POST",
        url: url_form_detail,
        data: {id : id},
        // dataType: 'json',
        beforeSend: function() {
            $('.ajax_loader').show();
        },
        success: function(result){
            $('#seeDetail .modal-body').html(result);
            $('#seeDetail').modal('show');
            $('.ajax_loader').hide();
        }
    });
}