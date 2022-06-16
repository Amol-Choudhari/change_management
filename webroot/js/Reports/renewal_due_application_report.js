
// Change on 5/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
$('.search_field').val('');

// for multiselect dropdown option

//create the dynamic path for ajax url (Done by pravin 03/11/2017) - added by Ankur Jangid 20/05/2021
var host = location.hostname;
var paths = window.location.pathname;
var split_paths = paths.split("/");
var path = "/"+split_paths[1]+"/"+split_paths[2];

$('#state').change(function(e){

    var state = $('#state').val();

    var form_data = $("#newly_added_firm").serializeArray();
    form_data.push({name: "state", value: state});

    $.ajax({
        type: "POST",
        url: path+"/showDistrictDropdown",
        data: form_data,
        success: function(response){
            $("#district").html(response);
        }
    });
});

$(document).ready(function () {

    $('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
    $('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });
    $('#renewal_due_application_report_table').DataTable();

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
    
