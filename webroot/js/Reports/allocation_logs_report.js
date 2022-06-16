$(document).ready(function () {

    // Change on 2/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
    $('.search_field').val('');

    $('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
    $('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });
    $('#allocation_logs_report_table').DataTable();

    $('#search_btn').click(function(){

        var from = $("#fromdate").val().split("/");
        var fromdate = new Date(from[2], from[1] - 1, from[0]);

        var from = $("#todate").val().split("/");
        var todate = new Date(from[2], from[1] - 1, from[0]);

        if(todate < fromdate){

            alert('Invalid Date Range Selection');
            return false;
        }
    });

    $('html, body').animate({
        scrollTop: $('#page-load').offset().top
    }, 'slow');

});
