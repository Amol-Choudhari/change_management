$("#esign_submit_btn").prop("disabled", true);
$("#plz_wait").hide();

    $("#okBtn_wo_esign").prop("disabled", true);//added 04-05-2018 by Amol

        // Get the modal
    var modal = document.getElementById('declarationModal');

    // Get the button that opens the modal
    var final_submit_btn = document.getElementById("final_submit_btn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    
    
    //added on 28-03-2018 by Amol
    // Get the modal
    var esign_or_not_modal = document.getElementById('esign_or_not_modal');
    // When the user clicks on the button, open the modal
    final_submit_btn.onclick = function() {
        esign_or_not_modal.style.display = "block";
        return false;
    }

    //added on 26-03-2018 by Amol
    var proceedbtn = document.getElementById('proceedbtn');
    proceedbtn.onclick = function() {
        if ($('#esign_or_not_optionYes').is(":checked")) {		

            esign_or_not_modal.style.display = "none";
            modal.style.display = "block";
            return false;
                                
        } else if ($('#esign_or_not_optionNo').is(":checked")) {
            
            $("#once_no").val(null);//set aadhar value to null
            esign_or_not_modal.style.display = "none";
            //updated on 04-05-2018 by Amol for modal without esign
            var declarationModal_wo_esign = document.getElementById('declarationModal_wo_esign');									
            declarationModal_wo_esign.style.display = "block";
            
            return false;									
        }
    }
    
    
    $("#declaration_check_box").change(function() {	

        if ($('#esign_or_not_optionYes').is(":checked")) {		
            var with_esign = 'yes';
                                
        } else if ($('#esign_or_not_optionNo').is(":checked")) {
            var with_esign = 'no';								
        }

        if ($(this).prop('checked') == true) {
            
            $("#plz_wait").show();
            
            var controller_name = "<?php echo $controller_name; ?>";
            var forms_pdf = "<?php echo $forms_pdf; ?>";
        
        //added this new ajax block to set with/without esign value on concent checkbox clicked
        //on 29-03-2018
        
        //updated on 28-05-2021 for Form Based Esign method
        //now direct called xml creation function from esigncontroller hereby
        //removed the call to cw-dialog.js function, no need now
        //applied multiple inner ajax calls

            $.ajax({
                type:'POST',
                async: true,
                cache: false,
                data:{with_esign:with_esign},
                url: "../esign/set_esign_or_not",
                beforeSend: function (xhr) { // for csrf token
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success: function() {
                    
                    $.ajax({
                        type:'POST',
                        async: true,
                        cache: false,
                        url: "../" + controller_name + "/" + forms_pdf,
                        beforeSend: function (xhr) { // for csrf token
                                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        },
                        success: function() {
                            
                            $.ajax({
                                type:'POST',
                                async: true,
                                cache: false,
                                url: "../esign/create_esign_xml_ajax",
                                beforeSend: function (xhr) { // for csrf token
                                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                                },
                                success: function(xmlresult) {
                                    
                                    xmlresult = JSON.parse(xmlresult);
                                    
                                    $("#eSignRequest").val('');
                                    $("#aspTxnID").val('');
                                    
                                    $("#eSignRequest").val(xmlresult.xml);
                                    $("#aspTxnID").val(xmlresult.txnid);
                                    
                                    $("#plz_wait").hide();
                                    $("#esign_submit_btn").prop("disabled", false);//enable esign button
                                    
                                }
                            });
                        }
                    });
                    
                }
                
            });
            
        }
        
        if ($(this).prop('checked') == false) {
            
            $("#esign_submit_btn").prop("disabled", true);
        }
    });
    
    $("#esign_submit_btn").click(function() {
        
        if (confirm("You are now Redirecting to CDAC Server for Esign Authentication")) {
            
            return true;
        } else{
            return false;
        }
    });
    
    $(".close").click(function() {
        $(".modal").hide();
        return false;
    });

//till here on 28-05-2021 for Form based method, and renoved unwanted scripts	

//for final submit without esign, added on 04-05-2018 by Amol
$("#declaration_check_box_wo_esign").change(function() {

$("#okBtn_wo_esign").prop("disabled", false);
        
if ($(this).prop('checked') == false) {
    
    $("#okBtn_wo_esign").prop("disabled", true);
}

});