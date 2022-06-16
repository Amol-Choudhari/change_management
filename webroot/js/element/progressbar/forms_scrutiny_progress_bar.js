var progbarstatus = document.getElementById('progbarstatus').value;
var progbarvalue = (progbarstatus != '') ? JSON.parse(progbarstatus) : Array();

var tableRw = JSON.parse(JSON.stringify(progbarvalue));

$.each(tableRw, function(index, value){

    var section_id =  value[0];
    var sectionvalue1 =  value[1];
    if( sectionvalue1 == '' || sectionvalue1 == null ){ sectionvalue1 = null; }
    var sectionvalue2 =  value[2];
    if( sectionvalue2 == '' || sectionvalue2 == null ){ sectionvalue2 = null; }

    $('#section'+section_id).removeClass();
    $('#span'+section_id).removeClass();


    if(sectionvalue1 == 'saved' && sectionvalue2 == null)
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-red');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue1 == 'saved' && sectionvalue2 != null)
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue1 == 'referred_back')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue1 == 'approved')
    {
      $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
      $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
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

});
