
var progbarstatus = document.getElementById('progbarstatus').value;
var pbfinalsubmit = document.getElementById('pbfinalsubmit').value;
var progbarvalue = (progbarstatus != '') ? JSON.parse(progbarstatus) : Array();

var tableRw = JSON.parse(JSON.stringify(progbarvalue));

$.each(tableRw, function(index, value){

    var section_id =  value[0];
    var sectionvalue =  value[1];

    $('#section'+section_id).removeClass();
    $('#span'+section_id).removeClass();

    if(sectionvalue == '')
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-red');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue == 'saved')
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue == 'referred_back' && pbfinalsubmit == "referred_back")
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue == 'referred_back' && pbfinalsubmit != "referred_back")
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue == 'approved')
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue == 'pending')
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue == 'not_confirmed')
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
    }
    else if(sectionvalue == 'replied')
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    else if(sectionvalue == 'confirmed')
    {
        $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
        $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
    }
    
});
