$(document).ready(function(){    
    var last_col_lang = $(".table-languages tr th").length;
    var last_col_groups = $(".table-groups tr th").length;
    var option_lang = [0];
    var option_groups = [0];
    var numb_column = 0;

    if ($('.column-manage').length > 0) { 
        option_lang.push(last_col_lang - 1);
        option_groups.push(last_col_groups - 1);
        last_col_groups = last_col_groups - 1;
    }
    
    option_groups.push(last_col_groups - 1);
    
    if (count_languages > 0) {
        datatable_option2('table-languages', option_lang, record_numb, numb_column);
    }
    if (count_groups > 0) {
        datatable_option2('table-groups', option_groups, record_numb, numb_column);
    }
});

function create_lang()
{
    $('.modal-content').load(url_create_lang, {create_lang : ''});
}

function create_group()
{
    $('.modal-content').load(url_create_group, {create_group : ''});
}

function edit_group(id, type)
{
    type = typeof type !== 'undefined' ? type : 'old';
    $.ajax({
        url : url_edit_group,
        type : 'post',
        data: {ajax_check_data : '', id : id},
        success : function(data) {
            if ($.parseJSON(data).type == 'error') {
                Command: toastr[$.parseJSON(data).type]($.parseJSON(data).text, $.parseJSON(data).title);
            } else if ($.parseJSON(data).type == 'success') {
                $('.modal-content').load(url_edit_group, {edit_group : '', id : id, type : type});
                $('#myModal').modal('show');
            }
        }
    });
}

function delete_group(id)
{
    var result = confirm(alert_delete);
    if (result) {
        $.ajax({
            url : url_delete_group,
            type : 'post',
            data : {_token : my_token, delete_group : '', id : id},
            success : function(data) {
                $('.table').DataTable().rows('#group'+id).remove().draw();
                gen_numb('[id="group_numb"]', 1);
                Command: toastr[$.parseJSON(data).type]($.parseJSON(data).text, $.parseJSON(data).title);
            }
        });
    }
}

function datatable_option2(class_name, column, numb, numb_column, is_numb)
{
    numb_column = typeof numb_column !== 'undefined' ? numb_column : 1;
    is_numb = typeof is_numb !== 'undefined' ? is_numb : true;
    
    $('.'+class_name).dataTable({
        "bFilter": false,
        "bPaginate": false,
        "bInfo": false,
        "bSort": true,
        "bSort": true,
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': column }
        ],
        "order": [],
        "fnDrawCallback": function ( oSettings ) {
            var that = this;
            if (is_numb) {
                /* Need to redo the counters if filtered or sorted */
                if ( oSettings.bSorted || oSettings.bFiltered )
                {
                    this.$('td:first-child', {"filter":"applied"}).each( function (i) {
                        that.fnUpdate( i + numb, this.parentNode, numb_column, false, false );
                    } );
                }
            }
        }
    });
}