$(document).ready(function(){
    $.ajaxSetup({
        beforeSend: function() 
        {
            $(".ajax_loader").show();
        },
        error: function(statusCode, errorThrown) {
            if (statusCode.status == 0) {
                toastr.clear();
                Command: toastr['error']('You are offline.', 'Error!')
            }
        },
        complete: function() 
        {
            $(".ajax_loader").hide();
        }
    });
});