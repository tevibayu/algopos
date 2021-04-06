$(document).ready(function(){
    $('#nestable').nestable({
        maxDepth:2
    });
    
    $('#form-area').load(siteurl+'/admin/menu/create');

    $('.disclose').on('click', function() {
        $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
        $(this).toggleClass('ui-icon-plusthick').toggleClass('ui-icon-minusthick');
    });

    $("#title").focus();
    
    $.ajaxSetup({
        beforeSend: function() 
        {
            $('.ajax_loader').show();
            $('[name="save_order"]').prop('disabled', true);
            $('[name="save"]').prop('disabled', true);
            $('[name="btn_cancel"]').prop('disabled', true);
        },
        error: function(statusCode, errorThrown) {
            if (statusCode.status == 0) {
                toastr.clear();
                Command: toastr['error']('You are offline.', 'Error!')
            }
        },
        complete: function() 
        {
            $('.ajax_loader').hide();
            $('[name="save_order"]').prop('disabled', false);
            $('[name="save"]').prop('disabled', false);
            $('[name="btn_cancel"]').prop('disabled', false);
        }
    });
});

function change_parent()
{
    if($("#parent_id").val()==0){
        $("#permission_id").prop("disabled",true);
    }else{
        $("#permission_id").prop("disabled",false);
    }
}

function save_order_menu(){
    $.ajax({
        url : siteurl + '/admin/menu/save_order',
        data : {data:$('#nestable').nestable('serialize')},
        dataType : "json",
        type : "post",
        success: function(msg){
            if (msg['type'] == 'success') {
                Command: toastr[msg['type']](msg['text'], msg['title']);
                setTimeout(function(){
                    window.location.reload();
                },2000);
            }
        }
    });
}

//Cancel Edit or Add
function cancel(){
    $("#menuid").val("");
    $("#type").val("add");
    $("#title").val("");
    $("#link").val("");
    $("#lang").val("");
    $("#icon").val("");
    $("#taget").val("sametab");
    $("#group_menu").val($("#group_menu option:first").val());
    $("#parent_id").val(0);
    //$("#permission_id").prop("disabled",true);
    $("#permission_id").val($("#permission_id option:first").val());
    $("#status").val(1);

    $("#frm_menu_head").html(create_menu);
}	

//Save Menu
function save_menu(){
    var title = $("#title").val().trim();
    var formdata = $("#frm_menu").serialize();

    $.ajax({
        url : siteurl+"/admin/menu/save_menu",
        data : formdata,
        dataType : "json",
        type : "post",
        success: function(msg) {
            Command: toastr[msg['type']](msg['text'], msg['title']);
            if (msg['type'] == 'success') {
                setTimeout(function(){
                    cancel();
                    window.location.reload();
                },2000);
            }
        }
    });
}

//Edit Menu
function editmenu(idmenu){
    if(idmenu != ''){
        $("#frm_menu_head").html(edit_menu);
        $("#form-area").load(siteurl+'/admin/menu/edit/'+idmenu);		
    }
}

//Delete Menu
function delete_menu(id){
    var conf = confirm(alert_delete);

    if (id !== '') {
        if (conf) {
            $.ajax({
                url : siteurl+'/admin/menu/delete',
                data : ({id: id, _token : my_token}),
                type : 'post',
                dataType : 'json',
                success : function(msg){
                    Command: toastr[msg['type']](msg['text'], msg['title']);
                    if (msg['type'] == 'success') {
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }
                }
            });
        }
    }
}