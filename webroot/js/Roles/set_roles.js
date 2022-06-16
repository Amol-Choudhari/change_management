
$(document).ready(function(){
    //created this code to compare user office type with existing user from same office
    //on 04-01-2020 by Amol
    $("input[type='radio']").click(function(){

        var office_type_val = $("input[type='radio']:checked").val();
        var user_id = $('#user_list').val();

        $.ajax({

            type: "POST",
            url: "../roles/check_office_type",
            data: {user_id:user_id,office_type_val:office_type_val},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success: function(response){
                var m = response.match(/"(.*?)"/); //to remove extra contents come with reponse
                if(m[1] != office_type_val && m[1] != null){
                    alert('Please select "'+m[1]+'" as office type for this user.');
                    $("input[type='radio']").prop('checked',false);
                }
            }
        });
    });


    $('#search_btn').click(function(){
        var search_file=$("#search_file").val();
        if(start_date==""){
            alert("Sorry...All search Fields are empty");
            return false;
        }
    });


// Start to Check entry of dy_ama, jt_ama, ama into user_roles tabels for duplicate set roles for dy_ama, jt_ama, ama into user_roles
// Done by pravin 30-08-2017

    var dyama_set_role_detail = $('#dyama_set_role_detail').val();

    $("#dy_ama").change(function() {

        if($(this).prop('checked') == true) {
            if(dyama_set_role_detail == '' || dyama_set_role_detail == null){ dyama_set_role_detail = null; }
                if(dyama_set_role_detail != null){
                    if(!alert('The role of Dy. AMA (QC) is already asigned to '+dyama_set_role_detail+'. Only one user ID can be allocated the role of Dy. AMA (QC)')){
                        $(this).prop('checked', false);
                    }
                }
        }
    });


    var jtama_set_role_detail = $('#jtama_set_role_detail').val();

    $("#jt_ama").change(function() {
        if($(this).prop('checked') == true) {

            if(jtama_set_role_detail == '' || jtama_set_role_detail == null){ jtama_set_role_detail = null; }

            if(jtama_set_role_detail != null){
                if(!alert('The role of Jt. AMA is already asigned to '+jtama_set_role_detail+'. Only one user ID can be allocated the role of Jt. AMA')){
                    $(this).prop('checked', false);
                }
            }
        }
    });

    var ama_set_role_detail = $('#ama_set_role_detail').val();

    $("#ama").change(function() {

        if($(this).prop('checked') == true) {

            if(ama_set_role_detail == '' || ama_set_role_detail == null){ ama_set_role_detail = null; }


             if(ama_set_role_detail != null){

                    if(!alert('The role of AMA is already asigned to '+ama_set_role_detail+'. Only one user ID can be allocated the role of AMA')){
                        $(this).prop('checked', false);
                    }
                }
            }
        });

        // End to Check entry of dy_ama, jt_ama, ama into user_roles tabels
    });



    //create the dynamic path for ajax url (Done by pravin 03/11/2017)
    var host = location.hostname;
    var paths = window.location.pathname;
    var split_paths = paths.split("/");
    var path = "/"+split_paths[1]+"/"+split_paths[2];

    $("#dmi_user_roles_list_box").hide();
    $("#lmis_user_roles_list_box").hide();
    $("#both_user_roles_list_box").hide();
    $("#set_roles_btn").css('display','none');

    $('#user_list').change(function(e){

    var user_id = $('#user_list').val();

    //added this condition on 18-07-2018 by pravin
    //to avoid blank id selection on 'select' option clicked
        if(user_id != ''){

            var form_data = $("#set_roles_form").serializeArray();
            form_data.push(	{name: "user_id",value: user_id});

            $.ajax({
                type: "POST",
                url: path+"/user_division_type",
                data: form_data,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success: function(response){

                    $("#user_division").html(response);
                    var user_type_text = $('#user_type_text').text();

                    if(user_type_text == "User Type : DMI"){
                        $("#dmi_user_roles_list_box").show();
                        $("#lmis_user_roles_list_box").hide();
                        $("#both_user_roles_list_box").show();

                    }else if(user_type_text == "User Type : LIMS"){

                        $("#dmi_user_roles_list_box").hide();
                        $("#lmis_user_roles_list_box").show();
                        $("#both_user_roles_list_box").show();

                    }else{

                        $("#dmi_user_roles_list_box").show();
                        $("#lmis_user_roles_list_box").show();
                        $("#both_user_roles_list_box").show();

                    }
                        $("#set_roles_btn").css('display','block');
                        $("#user_type_text").addClass("badge badge-secondary");
                },

                error: function(data) {
                    alert(data);
                }
            });

        }else{

            $("#dmi_user_roles_list_box").hide();
            $("#lmis_user_roles_list_box").hide();
            $("#both_user_roles_list_box").hide();
            $("#set_roles_btn").css('display','none');
            $("#user_division").hide();
        }
    });
