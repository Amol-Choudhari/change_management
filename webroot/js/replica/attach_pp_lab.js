$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        
        var inputValue = $(this).attr("value");
        
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
         
     //   $("#pp-lab").prop('disabled', true);
       
        $(targetBox).show();

        if ($("#pp-pp").is(":checked")) {
          
            $('#lab').prop('disabled', true);
            $('#pp').prop('disabled', false);
        }
        if ($("#pp-lab").is(":checked")) {
          
            $('#pp').prop('disabled', true);
            $('#lab').prop('disabled', false);
        }
        
        
    });
});