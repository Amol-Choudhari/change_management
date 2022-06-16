

$("#applicant_logs_table").dataTable({"order": []});

$("#replica_detail_popup").hide();

    $("#replica_details_btn").click(function(e){

        e.preventDefault();

        var rep_ser_no = $("#rep_ser_no").val();

        if(rep_ser_no==''){
            alert('Please enter replica serial number');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "../Ecode/search_replica",
            data: {rep_ser_no:rep_ser_no},
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
