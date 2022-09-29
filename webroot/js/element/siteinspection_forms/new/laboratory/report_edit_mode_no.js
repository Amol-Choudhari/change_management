$( document ).ready(function() {

        $("#form_inner_main :input").prop("disabled", true);
        $("#form_inner_main :input[type='radio']").prop("disabled", true);
        $("#form_inner_main :input[type='select']").prop("disabled", true);
        $("#form_inner_main :input[type='submit']").prop("disabled", true);
        $("#form_inner_main :input[type='reset']").prop("disabled", true);
        $("#form_inner_main :input[type='button']").prop("disabled", true);

        $("#form_inner_main :input[type='submit']").hide();

        //below code added on 07-08-2017 by Amol
        $(".director_edit").css('display','none');
        $(".director_delete").css('display','none');
        $("#add_new_row").css('display','none');
        $("#add_directors_details").css('display','none');


});
