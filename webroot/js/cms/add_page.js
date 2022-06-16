$('#publish_btn').click(function (e) { 
  
    if (add_edit_pages_validation() == false) {
        e.preventDefault();
    } else {
        $('#add_page').submit();
    }
});
    

ClassicEditor.create( document.querySelector( '#editor' ) ).catch( error => {
            console.error( error );
} );

$(document).ready(function () {
        
    //alert('here');
    $('#publish_date').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true
    });
    
    
    $('#archive_date').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true
    });
    
    
});

var config = {
    '.chosen-select'           : {},
    '.chosen-select-deselect'  : {allow_single_deselect:false},
    '.chosen-select-no-single' : {disable_search_threshold:10},
    '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
    '.chosen-select-width'     : {width:"95%"}
  }
  for (var selector in config) {
    $(selector).chosen(config[selector]);
    
  } 
  
  //to get the path of selected file    
  $('#copy_file_path').hide();
  
  $('#file_path').change(function() {           
      
      $('#copy_file_path').val($(this).val());
      $('#copy_file_path').show();
  });
  