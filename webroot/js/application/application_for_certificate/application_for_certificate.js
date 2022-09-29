
var oldapplication = $('#oldapplication_call').val();

$( document ).ready(function() {

    var changelist = JSON.parse($("#changefields").val());

    if(changelist != ''){
        var buttonsArray = ['submit','reset','button'];
        var excludeId = ['esign_or_not_option-yes','esign_or_not_option-no','proceedbtn','okBtn_wo_esign','okBtn','cancelBtn'];
        var changeField = false;

        $("form :input").each(function(){
            var inputId = $(this).attr('id');
            var inputtype = $(this).attr('type');

            if($.inArray(inputId, changelist) == -1 && $.inArray(inputtype, buttonsArray) == -1 && $.inArray(inputId, excludeId) == -1){
                
                $("#"+inputId).prop("readonly", true);
                $("#"+inputId).css("pointer-events","none");
                $("#"+inputId).css("background","#e9ecef");
                $("#"+inputId).next(".custom-file-label").css("background-color","#e9ecef !important");
                $("#"+inputId).next("label").css("background-color","#e9ecef !important");
                $("#"+inputId).next("label").css("pointer-events","none");
                $("#"+inputId).next(".ms-options-wrap").css("pointer-events","none");
                $("#"+inputId).next(".ms-options-wrap").css("background-color","#e9ecef !important");
                $("#check_save_reply").css('display','block');
                $("#check_save_reply").prop("readonly", false);
                $("#check_save_reply").css("pointer-events","fill");
                $("#check_save_reply").css("background-color","#ffff !important");
                $("#cr_comment_ul").css('display','block');
                $("#cr_comment_ul").prop("readonly", false);
                $("#cr_comment_ul").css("pointer-events","fill");
                $("#cr_comment_ul").next("label").css("background-color","#ffff !important");
                $("#edit_cr_comment_ul").css('display','block');
                $("#edit_cr_comment_ul").prop("readonly", false);
                $("#edit_cr_comment_ul").css("pointer-events","fill");
                $("#edit_cr_comment_ul").next("label").css("background-color","#ffff !important");

            }else{

                if($.inArray(inputtype, buttonsArray) == -1 && $.inArray(inputId, excludeId) == -1){
                    changeField = true;
                    $("#"+inputId).css("border","1px solid red");
                    $("#"+inputId).parent(".custom-file").css("border","1px solid red");
                }
            }
        });

        if(changeField == false){
            $("#form_outer_main :input[type='submit']").css('display','none');
            $("#save_btn").css('display','none');
        }

    }
});