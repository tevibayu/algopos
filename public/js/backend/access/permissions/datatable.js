$(document).ready(function(){    
    var last_col = $("#table tr th").length;
    var option 	= [0];
    var numb_column = 0;

    if ($('.column-check').length > 0) { 
        option.push(1); 
        numb_column = 1; 
    }
    if ($('.column-manage').length > 0) { 
        option.push(last_col - 1);
    }

    datatable_option2(option, record_numb, numb_column);
});

function datatable_option2(column, numb, numb_column, is_numb)
{
    numb_column = typeof numb_column !== 'undefined' ? numb_column : 1;
    is_numb = typeof is_numb !== 'undefined' ? is_numb : true;

    $('#table').dataTable({
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