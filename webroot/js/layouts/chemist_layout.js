$( document ).ready(function() {
 $("#Token_key_id").prop("disabled", false);
});

//to disable right click of all anchor tags// on 11-11-2020 by Amol
  $(document).bind("contextmenu",function(e){
      return false;
  });

  var process_query = $('#process_query').val();
  //to hide Reset button if that section saved for all CA/printing/Lab sections
  //created on 10-10-2017 by Amol
  if(!empty(process_query)){

      if(process_query == 'update') {

          $(document).ready(function() {
              $("#form_outer_main :input[type='reset']").hide();
          });
      }
  }
