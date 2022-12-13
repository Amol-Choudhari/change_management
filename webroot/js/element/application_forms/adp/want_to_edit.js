// User want to edit data or update 
// DESCRIPTION : FOR CHECKING THE SESSION IF SESSION IS YES , RETURN THE YES OR NO AND enabled all form fields
// @AUTHOR : SHANKHPAL SHENDE
// DATE : 24-11-2022
$(document).ready(function(){

     

    $('#wanttoedit').click(function(){
        enable_disable();
    });
    
    function enable_disable()
    { 
        var checkeditsession = $('#checkeditsession').val();
        if(checkeditsession == 'yes'){
                    
            $("#form_outer_main :input[type='submit']").show();
            $("#form_outer_main :input[type='radio']").prop("disabled", false);
            $("#form_outer_main :input[type='select']").prop("disabled", false);
            $("#form_outer_main :input[type='submit']").css('display','none');
            $("#form_outer_main :input[type='button']").prop("disabled", false);
            $(".form_outer_main .custom-file").css('display','none');
            $("#add_directors_details").show();
            $("#save_btn").show();
            $("#reset_btn").show();
            $(".glyphicon-edit").show();
            $("#other_information").prop("disabled", false);
            $("#form_outer_main :input[type='text']").prop("disabled", false);
            $(".table_record_add_btn").show();
            $("#form_outer_main :input[type='file']").prop("disabled", false);
            $("#form_outer_main .file_limits").show();
            $("#add_new_row_r").show();
            $(".packer_delete").show();
            $("#add_person_details").show();
            $('#add_person_details').removeAttr('disabled');
            $('#edit_person_details').show();
            $('#edit_person_details').removeAttr('disabled');
            $(".acols").show();


        }else{
                
                $.ajax({
                    type : 'POST',
                    url:"../AjaxFunctions/check-IfSesion-IsExists",
                    async : false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },
                    success: function() {
                        location.reload(); 
                     }

                });
        } 
                
            
    }

});


 

window.onload = function () { 
    
    var checkeditsession = $('#checkeditsession').val();
    if(checkeditsession == 'yes'){
        $('#wanttoedit').click();
        $('#wanttoedit').hide();
     }
}

   

   

