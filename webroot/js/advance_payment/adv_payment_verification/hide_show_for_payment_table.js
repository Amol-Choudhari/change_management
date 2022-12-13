$("#pending_list_table_data").dataTable({"order": []});
	$("#notconfirm_list_table_data").dataTable({"order": []});
	$("#replied_list_table_data").dataTable({"order": []});
	$("#confirm_list_table_data").dataTable({"order": []});



    $(document).ready(function(){

        $("#notconfirm_list_table").hide();
        $("#replied_list_table").hide();
        $("#confirm_list_table").hide();
        $("#pending_button").css({"backgroundColor":"#ffc256","color":"white"});

        $("#pending_button").click(function(){

            $("#pending_list_table").show();
            $("#notconfirm_list_table").hide();
            $("#replied_list_table").hide();
            $("#confirm_list_table").hide();
            $("#pending_button").css({"backgroundColor":"#ffc256","color":"white"});
            $("#notconfirmed_button").css({"backgroundColor":"#fff","color":"black"});
            $("#replied_button").css({"backgroundColor":"#fff","color":"black"});
            $("#confirmed_button").css({"backgroundColor":"#fff","color":"black"});
        });

        $("#notconfirmed_button").click(function(){

            $("#pending_list_table").hide();
            $("#notconfirm_list_table").show();
            $("#replied_list_table").hide();
            $("#confirm_list_table").hide();
            $("#notconfirmed_button").css({"backgroundColor":"#dc3545","color":"white"});
            $("#replied_button").css({"backgroundColor":"#fff","color":"black"});
            $("#pending_button").css({"backgroundColor":"#fff","color":"black"});
            $("#confirmed_button").css({"backgroundColor":"#fff","color":"black"});
        });

        $("#replied_button").click(function(){

            $("#pending_list_table").hide();
            $("#notconfirm_list_table").hide();
            $("#replied_list_table").show();
            $("#confirm_list_table").hide();
            $("#replied_button").css({"backgroundColor":"#6c757d","color":"white"});
            $("#notconfirmed_button").css({"backgroundColor":"#fff","color":"black"});
            $("#pending_button").css({"backgroundColor":"#fff","color":"black"});
            $("#confirmed_button").css({"backgroundColor":"#fff","color":"black"});

        });

        $("#confirmed_button").click(function(){

            $("#pending_list_table").hide();
            $("#notconfirm_list_table").hide();
            $("#replied_list_table").hide();
            $("#confirm_list_table").show();
            $("#confirmed_button").css({"backgroundColor":"#28a745","color":"white"});
            $("#notconfirmed_button").css({"backgroundColor":"#fff","color":"black"});
            $("#pending_button").css({"backgroundColor":"#fff","color":"black"});
            $("#replied_button").css({"backgroundColor":"#fff","color":"black"});
        });
});