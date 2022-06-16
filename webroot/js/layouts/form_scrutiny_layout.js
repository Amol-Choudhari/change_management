

$.widget.bridge('uibutton', $.ui.button);

$('#calendar').datepicker({
});

!function ($) {
    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
        $(this).find('em:first').toggleClass("glyphicon-minus");      
    }); 
    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
}(window.jQuery);

$(window).on('resize', function () {
  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
});

$(window).on('resize', function () {
  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
});


$(document).ready(function(){
			
    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
        
        //$('.table-format td').addClass('display_none');
    })

});


var currcontroller = $("#currcontroller").val();

if(currcontroller == 'scrutiny' )
{
    $(document).ready(function(){
			
        $('#tab-1').addClass('current');
        $('#tab-1-content').addClass('current');	
    
    });

}
else if(currcontroller == 'inspections')
{
    $(document).ready(function(){
			
        $('#tab-2').addClass('current');
        $('#tab-2-content').addClass('current');	
    
    });
}

//below script used to disable all mouse events for 10 sec, if any submit click.
//to prevent user from clicking any where while submit in process.
//created on 24-11-2017 by Amol
$(":submit").click(function() {
    $('.main_container').css('pointer-events','none');				
    setTimeout(function(){ $('.main_container').css('pointer-events','visible'); },4000);
    //to disable right click of all anchor tags// on 14-02-2018
    //$(document).bind("contextmenu",function(e){
    //return false;
});


// Transfer the application to respective Ro office
$(".tras-to-ro").on("click", function(){
        
    $('.mod').fadeIn('slow');
    
    $.ajax({
        type: "POST",
        url: "../AjaxFunctions/transferAppToROOffice",        
        beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        }, 
        success: function(response){

            if(response == 1){

                alert("Application successfully transfer to RO office");
                window.location = "../dashboard/home";
            }
            else
            {   
                alert("Application not transfer to RO office");
                location.reload();
            }
            
        }                             
    }); 
})