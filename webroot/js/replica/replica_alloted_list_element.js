$("#applicant_logs_table").dataTable({"order": []});//to display list as it is in result array order

$("#replica_detail_popup").hide();

$("#replica_details_btn").click(function(e){

    e.preventDefault();

    var rep_ser_no = $("#rep_ser_no").val();

    var alloted_list_for = $('#alloted_list_for').val(); //added for the alert message variable by AKASH on 30-12-2021

    if(rep_ser_no==''){
        $.alert('Please enter '+ alloted_list_for +' number');
        return false;
    }

    $.ajax({
        type: "POST",
        url: "../replica/search_replica",
        data: {rep_ser_no:rep_ser_no},
        beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success: function(response){

            var response = response.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response

            $("#replica_detail_popup").show();
            $("#append-table").html(response);

        }
    });

});

$(".close").click(function() {
    $(".modal").hide();
    return false;
});
