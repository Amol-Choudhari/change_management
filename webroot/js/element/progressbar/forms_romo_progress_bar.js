var progbarstatus = document.getElementById('progbarstatus').value;
var progbarvalue = (progbarstatus != '') ? JSON.parse(progbarstatus) : Array();

var tableRw = JSON.parse(JSON.stringify(progbarvalue));

$.each(tableRw, function(index, value){

    var section_id =  value[0];

    var sectionvalue1 =  value[1];
    if(sectionvalue1 == '' || sectionvalue1 == null){ sectionvalue1 = null; }
    var sectionvalue3 =  value[3];
    if(sectionvalue3 == '' || sectionvalue3 == null){ sectionvalue3 = null; }
    var sectionvalue4 =  value[4];
    if(sectionvalue4 == '' || sectionvalue4 == null){ sectionvalue4 = null; }
    var sectionvalue5 =  value[5];
    if(sectionvalue5 == '' || sectionvalue5 == null){ sectionvalue5 = null; }

    $('#section'+section_id).removeClass();
    $('#span'+section_id).removeClass();

    if(sectionvalue1 == 'approved' && sectionvalue3 == 'level_1')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue4 !=null && sectionvalue5 ==null && sectionvalue1 !='approved')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue4 ==null && sectionvalue5 !=null)
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue4 != null && sectionvalue5 != null)
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue1 == 'pending')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue1 == 'not_confirmed')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue1 == 'replied')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue1 == 'confirmed')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-red');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    
});
