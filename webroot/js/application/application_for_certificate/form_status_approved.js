$( document ).ready(function() {

$("#form_outer_main :input").prop("disabled", true);
$("#form_outer_main :input[type='radio']").prop("disabled", true);
$("#form_outer_main :input[type='select']").prop("disabled", true);
$("#form_outer_main :input[type='submit']").css('display','none');
$("#form_outer_main :input[type='button']").prop("disabled", true);
$(".form_outer_main .custom-file").css('display','none');
$("#verified").css('display','none');
$("#save_btn").css('display','none');
$("#add_directors_details").css('display','none');
$("#reset_btn").css('display','none');
$(".glyphicon-edit").css('display','none');
$(".glyphicon-remove-sign").css('display','none');
$(".table_record_add_btn").css('display','none');
$(".comment_reply_edit_btn").css('display','block');
$(".comment_reply_delete_btn").css('display','block');
$("#add_new_row_r").css('display','none');
$(".acols").css('display','none');
});