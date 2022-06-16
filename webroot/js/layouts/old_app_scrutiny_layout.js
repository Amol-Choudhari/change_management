$.widget.bridge('uibutton', $.ui.button);
var csrfToken = $('#bottom_layout_csrf_call').val();

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


$(":submit").click(function() {
    $('.main_container').css('pointer-events','none');
    setTimeout(function(){ $('.main_container').css('pointer-events','visible'); },4000);
});