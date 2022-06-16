$( document ).ready(function() {
    $("#actiob_buttons_x :input[type='submit']").prop("disabled", false);
    $("#actiob_buttons_x :input[type='submit']").css('display','block');
    $("#ro_referred_back_click").css('display','block');
    $("#ro_reply_click").css('display','block');
    $("#forward_comment").css('display','block');
    $("#edit_comment").css('display','block');
    $("#ro_referred_back").hide();//added on 18-05-2021 by Amol
    $("#ro_reply").hide();//added on 18-05-2021 by Amol
    $("#verified").hide();//added on 18-05-2021 by Amol
});	
