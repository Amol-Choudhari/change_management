$(document).ready(function(){

    $('#all_users_list').DataTable();
});


$('.deactivate_button').click(function (e) { 

    if (confirm('Are You Sure to Deactivate this user?')) {
        ////
    } else {
        return false;
        exit;
    }
    
});


$('.activate_button').click(function (e) { 

    if (confirm('Are You Sure to Activate this user?')) {
        ////
    } else {
        return false;
        exit;
    }
    
});


    