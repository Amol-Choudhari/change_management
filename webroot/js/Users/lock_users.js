$(document).ready(function(){

    $('#locked_user_list').DataTable();

    $("#unlock").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

});
