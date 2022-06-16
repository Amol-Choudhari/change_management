
    $("#user_logs_table").dataTable({"order": []});	//to display list as it is in result array order

$(document).ready(function () {
    $('#from_dt').datepicker({

        format: "dd/mm/yyyy",
        autoclose: true
    });

    $('#to_dt').datepicker({

        format: "dd/mm/yyyy",
        autoclose: true
    });

    $('#search').click(function(){

        if($('#from_dt').val()=='' || $('#to_dt').val()==''){
            $.alert('Please Select Proper Dates');
            return false;
        }
    });

});

var return_error_msg = $('#return_error_msg').val();
            
if(return_error_msg != ''){
    $.alert(return_error_msg);
}