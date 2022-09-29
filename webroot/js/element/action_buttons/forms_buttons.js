var validationFunction = $('#validationFunction').val();

    //The value is splitted to get the validataion function string name. which is getting from the Datbase.
    //The spliting of value is used for the function name string so its can be use as function.
    //Added by the Akash P Thakre on 27-10-2021

    var splitValue = validationFunction.split('()'); 
    var section_form_id = $('#section_form_id').val();

    //The Splitted Value is i.e "whatever validation function name stored in the database".
    //converting the string to a pointer by window[<method name>].
    //And the it can be use as variable with function ().

    var validationFunctionString = splitValue[0];
    var validations = window[validationFunctionString];


    $('#save_btn').click(function (e) {            

        if(validations() == false){
            e.preventDefault();

        }else{

            section_form_id.submit();
        }
    });
    

    var all_section_status = $('#all_section_status').val(); 
    var final_submit_status = $('#final_submit_status').val();
    var final_granted_btn = $('#final_granted_btn_forms_button').val();
    var current_level = $('#current_level').val();
    var forward_to_btn = $('#forward_to_btn_forms_button').val();
    var authRegFirm = $('#authRegFirm').val();


    if (all_section_status == 1 && (final_submit_status == 'no_final_submit' || final_submit_status == 'referred_back')) {
        
        $("#final_submit_btn").addClass("d-flex");
    }


    if (final_granted_btn == 'yes' || current_level == 'level_3') {
        
        $('#final_granted_btn').show();
    }

    if(forward_to_btn == '' || forward_to_btn == null){ forward_to_btn = null; }

    if (forward_to_btn != null || current_level == 'level_3') {
        
        $('#accepted_forward_btn').show();
    }


    if (authRegFirm == 'yes') {
        
        $('#accepted_forward_btn').hide();
        $('#final_granted_btn').hide();

    }

