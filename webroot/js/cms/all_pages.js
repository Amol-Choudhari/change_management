
$(document).ready(function(){

$('#pages_list_table').DataTable();

// For date picker option
$('#publish_date').datepicker({
    format: "dd/mm/yyyy"
});


$('#end_date').datepicker({
    format: "dd/mm/yyyy"
});
    

        
});


$('.delete_page').click(function (e) { 

    if (confirm('Are You Sure Delete This Page record ')) {
        ////
    } else {
        return false;
        exit;
    }
    
});


    