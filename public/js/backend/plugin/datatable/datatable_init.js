$(document).ready(function(){    
    var last_col = $(".table tr th").length;
    var option 	= [0];
    var numb_column = 0;

    if ($('.column-check').length > 0) { 
        option.push(1); 
        numb_column = 1; 
    }
    if ($('.column-manage').length > 0) { 
        option.push(last_col - 1);
    }

    datatable_option(option, record_numb, numb_column);
});