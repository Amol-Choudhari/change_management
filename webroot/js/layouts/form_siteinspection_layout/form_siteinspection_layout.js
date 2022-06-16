  $.widget.bridge('uibutton', $.ui.button)

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
})
$(window).on('resize', function () {
  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
})


$(document).ready(function(){

    bsCustomFileInput.init();

    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');

        //$('.table-format td').addClass('display_none');
    })

});


//below script used to disable all mouse events for 10 sec, if any submit click.
//to prevent user from clicking any where while submit in process.
//created on 24-11-2017 by Amol
$(":submit").click(function() {
    $('.main_container').css('pointer-events','none');
    setTimeout(function(){ $('.main_container').css('pointer-events','visible'); },4000);
});


//to disable right click of all anchor tags// on 14-02-2018
//$(document).bind("contextmenu",function(e){
//return false;
//});
