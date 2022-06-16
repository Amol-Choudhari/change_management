//get new field indication
function field_indication(){
			
    $("#userid_indication").show().text("user id ex: 210/2016 or 210/1/NGP/001 ");
    $("#userid_indication").css({"color":"blue","font-size":"13px","text-align":"right","font-weight":"500"});
    setTimeout(function(){ $("#userid_indication").fadeOut();},15000);
}			
   
//get new captcha
function get_new_captcha(){	

    $.ajax({

        type: "POST",
        async:true,
        url:"refresh_captcha_code",					
        beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        }, 
        success: function (data) {
                $("#captcha_img").html(data);
        }
    });
}

//call to login validations
$('.myfunctionclick').click(function (e) {
    
    if (login_users_validations() == false) {
        e.preventDefault();
    } else {
      $('#login_user_form').submit();  
    }     
});

//Call to new Captcha
$('#new_captcha').click(function (e) { 
    e.preventDefault();
    get_new_captcha()
});