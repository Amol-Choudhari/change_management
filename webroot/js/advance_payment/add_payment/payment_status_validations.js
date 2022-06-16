$( document ).ready(function() {

    $("#form_outer_main :input").prop("disabled", true);
    $("#form_outer_main :input[type='radio']").prop("disabled", true);
    $("#form_outer_main :input[type='select']").prop("disabled", true);
    $("#form_outer_main :input[type='submit']").prop("display", 'none');
    $("#final_submit_btn").css('display','none');
});