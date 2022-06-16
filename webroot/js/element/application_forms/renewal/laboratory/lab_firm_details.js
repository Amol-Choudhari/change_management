$( document ).ready(function() {
    $("#form_outer_main :input").prop("disabled", true);
    $("#form_outer_main :input[type='select']").prop("disabled", true);
    $("#save_btn").css("display", 'none');
    $("#reset_btn").css("display",'none');
});
