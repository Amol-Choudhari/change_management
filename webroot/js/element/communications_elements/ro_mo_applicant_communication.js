$("#edit_rb_comment_ul").change(function(){

    file_browse_onclick('edit_rb_comment_ul');
    return false;
});

$("#rb_comment_ul").change(function(){

    file_browse_onclick('rb_comment_ul');
    return false;
});

$("#edit_rr_comment_ul").change(function(){

    file_browse_onclick('edit_rr_comment_ul');
    return false;
});

$("#rr_comment_ul").change(function(){

    file_browse_onclick('rr_comment_ul');
    return false;
});

$("#edit_mo_comment_ul").change(function(){

    file_browse_onclick('edit_mo_comment_ul');
    return false;
});

$("#mo_comment_ul").change(function(){

    file_browse_onclick('mo_comment_ul');
    return false;
});

$('#save_edited_referred_back').click(function (e) { 
    if (comment_reply_ro_to_applicant_box_validation() == false) {
        e.preventDefault();
    }
    
});

$('#save_edited_ro_reply').click(function (e) { 
    if (comment_reply_box_validation() == false) {
        e.preventDefault();
    }
    
});

$('#ro_reply').click(function (e) { 
    if (comment_reply_box_validation() == false) {
        e.preventDefault();
    }
    
});

$('#save_edited_mo_comment').click(function (e) { 
    if (comment_reply_box_validation() == false) {
        e.preventDefault();
    }
    
});

$('#mo_referred_back').click(function (e) { 
    if (comment_reply_ro_to_applicant_box_validation() == false) {
        e.preventDefault();
    }
    
});

$('#edit_rb_comment_ul').change(function() {				
    $('#rb_comment_label').text('File Selected');
});
$('#edit_rr_comment_ul').change(function() {				
    $('#rr_comment_label').text('File Selected');
});
$('#edit_mo_comment_ul').change(function() {				
    $('#mo_comment_label').text('File Selected');
});