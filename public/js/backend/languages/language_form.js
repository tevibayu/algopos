function delete_lang(key)
{
    var result = confirm(alert_delete);
    if (result) {
        $('#lang'+key).remove();
        gen_numb('[id="record_numb"]', 1);
    }
}

function add_lang()
{
    var newtr = '';
    newtr += '<tr id="lang'+numb+'">';
    newtr += '<td id="record_numb"></td>';
    newtr += '<td><input type="text" name="key[]" class="form-control" /></td>';
    newtr += '<td><input type="text" name="value_en[]" class="form-control" /></td>';
    if (form_type == 'edit') {
        newtr += '<td><input type="text" name="value_'+code+'[]" class="form-control" /></td>';
    }
    newtr += '<td><a class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" data-original-title="Delete" onclick="delete_lang('+numb+')"><i class="fa fa-trash"></i></a></td>';
    newtr += '</tr>';
    $(newtr).fadeIn('slow').appendTo('#tbody');
    numb++;
    $("[data-toggle='tooltip']").tooltip();
    gen_numb('[id="record_numb"]', 1);
}